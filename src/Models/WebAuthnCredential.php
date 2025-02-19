<?php

namespace niyazialpay\WebAuthn\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\LazyCollection;
use MongoDB\Laravel\Collection;
use MongoDB\Laravel\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\Model;
use Laragear\MetaModel\CustomizableModel;
use MongoDB\Laravel\Relations\MorphTo;
use niyazialpay\WebAuthn\ByteBuffer;
use niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable;
use niyazialpay\WebAuthn\Events\CredentialDisabled;
use niyazialpay\WebAuthn\Events\CredentialEnabled;
use niyazialpay\WebAuthn\Migrations\WebAuthnAuthenticationMigration;

use function parse_url;

use const PHP_URL_HOST;

/**
 * @mixin Builder
 *
 * @method Builder|\static newQuery()
 * @method static Builder|\static query()
 * @method static \static make(array $attributes = [])
 * @method static \static create(array $attributes = [])
 * @method static \static forceCreate(array $attributes)
 * @method static \static forceCreateQuietly(array $attributes = [])
 * @method \static|null first($columns = ['*'], string ...$columns)
 * @method \static firstOrNew(array $attributes = [], array $values = [])
 * @method \static firstOrFail($columns = ['*'])
 * @method \static firstOrCreate(array $attributes, array $values = [])
 * @method \static firstOr($columns = ['*'], \Closure $callback = null)
 * @method \static firstWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method \static updateOrCreate(array $attributes, array $values = [])
 * @method \static createOrFirst(array $attributes, array $values = [])
 * @method \static sole($columns = ['*'])
 * @method \static findOrNew($id, $columns = ['*'])
 * @method Collection<int, \static>|\static[]|\static|null find($id, $columns = ['*'])
 * @method Collection<int, \static>|\static[]|\static findOrFail($id, $columns = ['*'])
 * @method Collection<int, \static>|\static[]|\static findOr($id, $columns = ['*'], \Closure $callback = null)
 * @method Collection<int, \static>|\static[] findMany($id, $columns = ['*'])
 * @method Collection<int, \static>|\static[] fromQuery($query, $bindings = [])
 * @method LazyCollection<int, \static>|\static[] lazy(int $chunkSize = 1000)
 * @method LazyCollection<int, \static>|\static[] lazyById(int $chunkSize = 1000, string|null $column = null, string|null $alias = null)
 * @method LazyCollection<int, \static>|\static[] lazyByIdDesc(int $chunkSize = 1000, string|null $column = null, string|null $alias = null)
 *
 * @property-read string $id
 * @property-read string $user_id
 * @property string|null $alias
 * @property-read int $counter
 * @property-read string $rp_id
 * @property-read string $origin
 * @property-read array<int, string>|null $transports
 * @property-read string $aaguid
 * @property-read string $public_key
 * @property-read string $attestation_format
 * @property-read array<int, string> $certificates
 * @property-read Carbon|null $disabled_at
 * @property-read ByteBuffer $binary_id
 * @property-read Carbon $updated_at
 * @property-read Carbon $created_at
 * @property-read WebAuthnAuthenticatable $authenticatable
 *
 * @method Builder|\static whereEnabled()
 * @method Builder|\static whereDisabled()
 */
class WebAuthnCredential extends Model
{
    use CustomizableModel;

    protected $collection = 'webauthn_credentials';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'counter' => 'int',
        'transports' => 'array',
        'public_key' => 'encrypted',
        'certificates' => 'array',
        'disabled_at' => 'timestamp',
    ];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var array<int, string>
     */
    protected $visible = ['id', 'origin', 'alias', 'aaguid', 'attestation_format', 'disabled_at'];

    /**
     * @phpstan-ignore-next-line
     *
     * @return MorphTo
     */
    public function authenticatable(): MorphTo
    {
        return $this->morphTo('authenticatable');
    }

    /**
     * Filter the query by enabled credentials.
     */
    protected function scopeWhereEnabled(Builder $query): Builder
    {
        // @phpstan-ignore-next-line
        return $query->whereNull('disabled_at');
    }

    /**
     * Filter the query by disabled credentials.
     */
    protected function scopeWhereDisabled(Builder $query): Builder
    {
        // @phpstan-ignore-next-line
        return $query->whereNotNull('disabled_at');
    }

    /**
     * Check if the credential is enabled.
     */
    public function isEnabled(): bool
    {
        return null === $this->attributes['disabled_at'];
    }

    /**
     * Check if the credential is disabled.
     */
    public function isDisabled(): bool
    {
        return ! $this->isEnabled();
    }

    /**
     * Enables the credential to be used with WebAuthn.
     */
    public function enable(): void
    {
        $wasDisabled = (bool) $this->attributes['disabled_at'];

        $this->attributes['disabled_at'] = null;

        $this->save();

        if ($wasDisabled) {
            CredentialEnabled::dispatch($this);
        }
    }

    /**
     * Disables the credential for WebAuthn.
     */
    public function disable(): void
    {
        $wasEnabled = ! $this->attributes['disabled_at'];

        $this->setAttribute('disabled_at', $this->freshTimestamp())->save();

        if ($wasEnabled) {
            CredentialDisabled::dispatch($this);
        }
    }

    /**
     * Sets the counter for this WebAuthn Credential.
     */
    public function syncCounter(int $counter): void
    {
        $this->attributes['counter'] = $counter;

        $this->save();
    }

    /**
     * Returns the RP ID attribute.
     *
     * @param  string  $rpId
     * @return string
     */
    protected function getRpIdAttribute(string $rpId): string
    {
        // If the Relying Party is a URL, we will return the domain, otherwise, verbatim.
        return ($domain = parse_url($rpId, PHP_URL_HOST)) ? $domain : $rpId;
    }

    /**
     * @inheritDoc
     */
    protected static function migrationClass(): string
    {
        return WebAuthnAuthenticationMigration::class;
    }
}
