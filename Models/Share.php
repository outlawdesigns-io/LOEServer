<?php namespace LOE;

require_once __DIR__ . '/../Base.php';
require_once __DIR__ . '/../Factory.php';
require_once __DIR__ . '/../Libs/JWT.php';
require_once __DIR__ . '/../Libs/StrUtilities.php';

class Share extends Base{

  const TABLE = 'Share';

  public $UID;
  public $userId;
  public $modelId;
  public $objectId;
  public $created_date;
  public $token;
  public $secret;
  public $expiration_date;
  protected $_object;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
  public static function generateSecret(){
    $str = '';
    for($i = 0; $i < 3; $i++){
      $str .= \StrUtilities::getRandomWord();
    }
    $secret = md5($str);
    if(!self::isSecretUnique($secret)){
      self::generateSecret();
    }
    return $secret;
  }
  public static function isSecretUnique($secret){
    $results = $GLOBALS['db']
      ->database(self::DB)
      ->table(self::TABLE)
      ->select(self::PRIMARYKEY)
      ->where('secret','=',"'" . $secret . "'")
      ->get();
    if(!mysqli_num_rows($results)){
      return true;
    }
    return false;
  }
  public static function buildShare($userId,$modelId,$objectId){
    $share = new self();
    $share->secret = self::generateSecret();
    $share->token = array(
      "userId"=>$userId,
      "modelId"=>$modelId,
      "objectId"=>$objectId
    );
    $share->token = \JWT::encode($share->token,$share->secret);
    return $share;
  }
  public static function getSecret($token){
    $results = $GLOBALS['db']
      ->database(self::DB)
      ->table(self::TABLE)
      ->select('secret')
      ->where('token','=',"'" . $token . "'")
      ->get();
    while($row = mysqli_fetch_assoc($results)){
      $secret = $row['secret'];
    }
    return $secret
  }
  public static function decodeToken($token,$secret){
    $data = \JWT::decode($token,$secret);
    $model = Factory::createModel(Model::TABLE,$data['modelId']);
    $object = Factory::createModel($model->label,$data['objectId']);
    return $object;
  }
}
