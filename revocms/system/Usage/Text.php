<?php
namespace Usage;

/**
 * Class Text
 * @package Usage
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class Text {

    /**
     * AutoLink Method
     *
     * @param string $text
     * @param bool|null $custom
     * @return mixed
     */

	public static function autoLink(string $text, bool $custom = null)
    {
        $regex = '@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@';
        
        if ($custom === null) {
            $replace = '<a href="http$2://$4">$1$2$3$4</a>';
        } //$custom === null
        else {
            $replace = '<a href="http$2://$4">' . $custom . '</a>';
        }
        
        return preg_replace($regex, $replace, $text);
    }

    /**
     * Slugify Method
     *
     * @param string $string
     * @return string
     */
	public static function slug(string $string) : string{
		$table = array(
				'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
				'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
				'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
				'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
				'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
				'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
				'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
				'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-', '+' => ''
		);
		$stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
		return strtolower(strtr($string, $table));
	}
}

