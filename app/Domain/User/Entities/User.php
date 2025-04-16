<?php

namespace App\Domain\User\Entities;

use App\Models\User as EloquentUser;
use Illuminate\Support\Carbon;

class User
{
    protected static $fields = [
        'name',
        'surname',
        'email',
        'phone',
        'country',
        'gender',
        'password',
        'selfie',
        'introduction',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    protected static $readable = [
        'id',
        'name',
        'surname',
        'email',
        'phone',
        'country',
        'gender',
        'selfie',
        'introduction',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    protected EloquentUser $model;

    public function __construct(
        public ?int $id,
        public string $name,
        public string $surname,
        public string $phone,
        public string $country,
        public string $gender,
        public string $email,
        public ?string $introduction,
        public ?string $password = null,
        public ?string $selfie = null,
        public ?Carbon  $email_verified_at = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}

    /**
     * Create and persist a new user.
     */
    public static function create(Array $data): static
    {
        $row = [];
        foreach (self::$fields as $field ) {
            if (isset($data[$field])) {
                $row[$field] = $data[$field];
            }
        }

        $model = new EloquentUser($row);

        $model->save();

        return self::fromModel($model);
    }

    /**
     * Save the user (update only).
     */
    public function update(Array $data): static
    {
        foreach (self::$fields as $field ) {
            if (isset($data[$field])) {
                $this->model->{$field} = $data[$field];
            }
        }
        $this->model->save();

        return self::fromModel($this->model);
    }

    /**
     * Delete the user.
     */
    public static function delete($id): void
    {
        if ($id) {
            EloquentUser::destroy($id);
        }
    }

    /**
     * Find a user by ID.
     */
    public static function find(int $id): ?static
    {
        $model = EloquentUser::find($id);
        
        return $model ? self::fromModel($model) : null;
    }

    /**
     * Get all users.
     */
    public static function all(): array
    {
        return EloquentUser::all()
            ->map(fn ($user) => self::fromModel($user))
            ->all();
    }

    /**
     * Convert Eloquent model to domain User entity.
     */
    public static function fromModel(EloquentUser $user): static
    {
        $row = [];
        foreach (self::$readable as $field ) {
            $row[$field] = $user->{$field};
        }

        $me = new static(...$row);
        $me->model = $user;
        return $me;
    }

    /**
     * Convert to array for output.
     */
    public function toArray(): array
    {
        $row = [];
        foreach (self::$readable as $field ) {
            $row[$field] = $this->{$field};
        }

        return $row;
    }
}
