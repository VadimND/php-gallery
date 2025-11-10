<?php
// Trait for set and get timestamp 
trait Timestampable
{
    protected $createdAt;
    protected $updatedAt;

    public function setTimestamps()
    {
        $now = new DateTime();
        if (!$this->createdAt) {
            $this->createdAt = $now;
        }
        $this->updatedAt = $now;
    }

    public function getCreatedAt()
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }
}

// Trait for remove mark
trait SoftDelete
{
    protected $deletedAt = null;

    public function delete()
    {
        $this->deletedAt = new DateTime();
        return 'Запись помечена как удаленная';
    }

    public function restore()
    {
        $this->deletedAt = null;
        return 'Запись восстановлена';
    }

    public function isDeleted()
    {
        return $this->deletedAt !== null;
    }
}

class User
{
    use Timestampable, SoftDelete;

    private $id;
    private $name;
    private $email;

    public function __construct($name, $email)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->email = $email;
        $this->setTimestamps();
    }

    public function updateProfile($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->setTimestamps();
    }

    public function getInfo()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->getCreatedAt(),
            'is_deleted' => $this->isDeleted(),
        ];
    }
}

// Usage
$user = new User('Иван Иванов', 'ivan@example.com');
print_r($user->getInfo());

$user->updateProfile('Иван Петров', 'petrov@example.com');
echo $user->delete() . "\n";
print_r($user->getInfo());

echo $user->restore() . "\n";
print_r($user->getInfo());

/*
 * Array (
 *      [id] => 6910e8a8a782a
 *      [name] => Иван Петров
 *      [email] => petrov@example.com
 *      [created_at] => 2025-11-09 19:16:56
 *      [is_deleted] => )
 */
