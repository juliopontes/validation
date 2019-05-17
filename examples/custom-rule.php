<?php
require_once dirname(__DIR__) . '/src/autoload.php';

use Validation\Validator;
use Validation\Rule;

class UniqueRule extends Rule
{
    /**
     * @var string
     */
    protected $message = ":attribute :value has been used";

    /**
     * @var array
     */
    protected $fillableParams = ['table', 'column', 'except'];

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * UniqueRule constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param $value
     * @return bool
     */
    public function check($value)
    {
        // make sure required parameters exists
        $this->checkRequiredParams();

        // getting parameters
        $column = $this->parameter('column');
        $table = $this->parameter('table');
        $except = $this->parameter('except');

        if ($except AND $except == $value) {
            return true;
        }

        // do query
        $stmt = $this->pdo->prepare("select count(*) as count from `{$table}` where `{$column}` = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // true for valid, false for invalid
        return intval($data['count']) === 0;
    }
}

$pdo = new PDO("mysql:host=localhost;dbname=exercicio", "root", "root");





Validator::addValidator('unique', new UniqueRule($pdo));

$validation = Validator::make($_REQUEST, [
    'email' => 'email|unique:users,email'
]);

