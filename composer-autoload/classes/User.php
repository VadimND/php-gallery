<?php

namespace App;

class User
{
    public string $email;
    public string $name;
    public ?int $id = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public ?string $email_verified_at = null;
    public ?string $password = null;
    public ?string $remember_token = null;
}