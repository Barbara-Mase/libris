<?php


use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

//Useful ine case model User.php is modified
//PHP is typed language so it's not useful to test invalid types. PHP will raise an exception on his own
class UserTest extends TestCase {
    #[DataProvider('validUsernameProvider')]
    public function testCanBeSetFromValidUsername($username) : void  {

        $user = new User("usernameTest", "email@test.com", "password", "intro", 1);
        $user->setUsername($username);
        $this->assertEquals($username, $user->getUsername());
    }

    //usernameProvider contains datas tested by testCanBeSetFromValidUsername
    public static function validUsernameProvider() : array {

        return [
            'username' => ["username"],
            'usernameWithUnderscore' => ["username__"],
            'usernameWithNumbers' => ["username2604"],
            'numbers' => ["2604"],
        ];
    }

    #[DataProvider('validEmailProvider')]
    public function testCanBeSetFromValidEmail($email) : void  {

        $user = new User("usernameTest", "email@test.com", "password", "intro", 1);
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
    }

    public static function validEmailProvider() : array {
        return [
            'email' => ["email@test.com"],
            'emailWithNumber' => ["email12@test.com"],
            'emailWithDots' => ["email.test@test.com"],
        ];
    }

    #[DataProvider('validPasswordProvider')]
    public function testCanBeSetFromValidPassword($password) : void  {

        $user = new User("usernameTest", "email@test.com", "password", "intro", 1);
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    public static function validPasswordProvider() : array {
        return [
            'password' => ["password"],
            'passwordWithUnderscore' => ["password__"],
            'passwordWithNumbers' => ["password2604"],
            ];
    }

    #[DataProvider('validIntroProvider')]
    public function testCanBeSetFromValidIntro($intro) : void  {

        $user = new User("usernameTest", "email@test.com", "password", "intro", 1);
        $user->setIntro($intro);
        $this->assertEquals($intro, $user->getIntro());
    }

    public static function validIntroProvider() : array {
        return [
            'intro' => ["Hello World"],
            'introWithExclamation' => ["Hello World!"],
            'emailWithNumber' => ["Hello World2604"],
            'emailWithUnderscore' => ["Hello World__"],
            'emailWithDot' => ["Hello World."],
        ];
    }

    #[DataProvider('validIdProvider')]
    public function testCanBeSetFromValidId($id) : void  {

        $user = new User("usernameTest", "email@test.com", "password", "intro", 1);
        $user->setId($id);
        $this->assertEquals($id, $user->getId());
    }

    public static function validIdProvider() : array {
        return [
            'id' => [1],
            'anotherId' => [12],
        ];
    }

    #[DataProvider('validRoleProvider')]
    public function testSetRoleAcceptsValidRole(string $role): void
    {
        $user = new User("usernameTest", "email@test.com", "password", "intro", 1);
        $user->setRole($role);
        $this->assertEquals($role, $user->getRole());
    }

    public static function validRoleProvider(): array
    {
        return [
            'admin' => ['ADMIN'],
            'user' => ['USER'],
        ];
    }

//    #[DataProvider('registrationDateProvider')]
//    public function testSetRoleAcceptsValidRegistrationDate(DateTime $registrationDate): void
//    {
//        $user = new User("usernameTest", "email@test.com", "password", "intro", 1);
//        $user->setRegistrationDate($registrationDate);
//        $this->assertEquals($registrationDate, $user->getRegistrationDate());
//    }
//
//    public static function registrationDateProvider(): array
//    {
//        return [
//            'date' => [26-04-1993],
//            'anotherDate' => [08-05-2003],
//        ];
//    }

}