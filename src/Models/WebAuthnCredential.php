<?php

namespace niyazialpay\WebAuthn\Models;

use MongoDB\Laravel\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\MorphTo;
use niyazialpay\WebAuthn\Events\CredentialDisabled;
use niyazialpay\WebAuthn\Events\CredentialEnabled;

/**
 * @mixin \MongoDB\Laravel\Eloquent\Builder
 *
 * @method static \MongoDB\Laravel\Eloquent\Builder|static query()
 * @method \MongoDB\Laravel\Eloquent\Builder|static newQuery()
 * @method static static make(array $attributes = [])
 * @method static static create(array $attributes = [])
 * @method static static forceCreate(array $attributes)
 * @method \niyazialpay\WebAuthn\Models\WebAuthnCredential firstOrNew(array $attributes = [], array $values = [])
 * @method \niyazialpay\WebAuthn\Models\WebAuthnCredential firstOrFail($columns = ['*'])
 * @method \niyazialpay\WebAuthn\Models\WebAuthnCredential firstOrCreate(array $attributes, array $values = [])
 * @method \niyazialpay\WebAuthn\Models\WebAuthnCredential firstOr($columns = ['*'], \Closure $callback = null)
 * @method \niyazialpay\WebAuthn\Models\WebAuthnCredential firstWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method \niyazialpay\WebAuthn\Models\WebAuthnCredential updateOrCreate(array $attributes, array $values = [])
 * @method ?static first($columns = ['*'])
 * @method static static findOrFail($id, $columns = ['*'])
 * @method static static findOrNew($id, $columns = ['*'])
 * @method static ?null find($id, $columns = ['*'])
 *
 * @property-read string $id
 *
 * @property-read string $user_id
 * @property string|null $alias
 *
 * @property-read int $counter
 * @property-read string $rp_id
 * @property-read string $origin
 * @property-read array<int, string>|null $transports
 * @property-read string $aaguid
 *
 * @property-read string $public_key
 * @property-read string $attestation_format
 * @property-read array<int, string> $certificates
 *
 * @property-read \Illuminate\Support\Carbon|null $disabled_at
 *
 * @property-read \niyazialpay\WebAuthn\ByteBuffer $binary_id
 *
 * @property-read \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Support\Carbon $created_at
 *
 * @property-read \niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable $authenticatable
 *
 * @method \MongoDB\Laravel\Eloquent\Builder|static whereEnabled()
 * @method \MongoDB\Laravel\Eloquent\Builder|static whereDisabled()
 */
class WebAuthnCredential extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'webauthn_credentials';

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
     * @return \MongoDB\Laravel\Relations\MorphTo|\niyazialpay\WebAuthn\Contracts\WebAuthnAuthenticatable
     */
    public function authenticatable(): MorphTo
    {
        return $this->morphTo('authenticatable');
    }

    /**
     * Filter the query by enabled credentials.
     *
     * @param  \MongoDB\Laravel\Eloquent\Builder  $query
     * @return \MongoDB\Laravel\Eloquent\Builder
     */
    protected function scopeWhereEnabled(Builder $query): Builder
    {
        // @phpstan-ignore-next-line
        return $query->whereNull('disabled_at');
    }
    /**
     * Filter the query by disabled credentials.
     *
     * @param  \MongoDB\Laravel\Eloquent\Builder  $query
     * @return \MongoDB\Laravel\Eloquent\Builder
     */
    protected function scopeWhereDisabled(Builder $query): Builder
    {
        // @phpstan-ignore-next-line
        return $query->whereNotNull('disabled_at');
    }

    /**
     * Check if the credential is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return null === $this->attributes['disabled_at'];
    }

    /**
     * Check if the credential is disabled.
     *
     * @return bool
     */
    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    /**
     * Enables the credential to be used with WebAuthn.
     *
     * @return void
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
     *
     * @return void
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
     * Increments the assertion counter by 1.
     *
     * @param  int  $counter
     * @return void
     */
    public function syncCounter(int $counter): void
    {
        $this->attributes['counter'] = $counter;

        $this->save();
    }
}
