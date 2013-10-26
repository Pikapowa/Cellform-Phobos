<?php
/**
 * CellForm Crypter Class Version 1.2
 * SHA-256 encryption class
 *
 * @author rootofgeno@gmail.com
 * @return
 */

class Cellform_Cypher
{
    private static $_skey = SALT;

	public static function Safe_b64encode($string)
	{
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public static function Safe_b64decode($string)
	{
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;

        if ($mod4)
		{
			$data .= substr('====', $mod4);
		}

        return base64_decode($data);
    }

    public static function Encode($value)
	{ 
        if (empty($value))
		{
			return false;
		}

        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $cypher = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::$_skey, $value, MCRYPT_MODE_ECB, $iv);
        return trim(self::Safe_b64encode($cypher)); 
    }

    public static function Decode($value)
	{
        if(empty($value))
		{
			return false;
		}

        $cypher = self::Safe_b64decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::$_skey, $cypher, MCRYPT_MODE_ECB, $iv);
        return trim($decrypt);
    }

	public static function GenerateToken($value)
	{
		$token = uniqid('', true);
		$token = self::Safe_b64encode($token) . $value;

		return $token;
	}

	public static function GenerateRand()
	{
		return time() . substr(self::Safe_b64encode(microtime()), 0, rand(5, 12));
	}

	public static function RandWord($lenght)
	{
		$string = '';
		$alphanum = 'abcdefghijklmnpqrstuvwxyz123456789';
		srand((double)microtime() * 1000000);

		for ($i=0; $i < $lenght; $i++)
		{
			$string .= $alphanum[rand() % strlen($alphanum)];
		}

		return $string;
	}
}

?>