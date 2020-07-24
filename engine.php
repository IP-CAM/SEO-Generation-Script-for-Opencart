<?php
/**
 * emrdev
 * @author		Mohammed Emomaliev
 * @web-site		https://www.emrdev.ru
 */

/*
 * Substitute your values or require config.php
 */
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'xxx');
define('DB_PASSWORD', 'xxxx');
define('DB_DATABASE', 'xxxx');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

define('LANGUAGE_ID', 1);
define('STORE_ID', 0);


$seo = new SeoGeneration();
$seo->debug = true;
$seo->start(['manufacturer','categories','products','article']); // all
//$seo->start(['products']); // only products
//$seo->start(['categories','products']); // only categories and products
//$seo->start(['categories','products','article']); // only categories, products and article


class SeoGeneration
{

    public $debug;
    private $connection;
    private $converter = [
        'а' => 'a', 'б' => 'b', 'в' => 'v',
        'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

        'А' => 'A', 'Б' => 'B', 'В' => 'V',
        'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
        'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
        'И' => 'I', 'Й' => 'Y', 'К' => 'K',
        'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R',
        'С' => 'S', 'Т' => 'T', 'У' => 'U',
        'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
        'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
    ];

    function __construct()
    {
        $this->connection = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $this->connection->set_charset("utf8");
        if($this->debug){
            echo '<pre>';
        }
    }

     private function debug(){
            echo '______________________Mysql_________________'.PHP_EOL;
            print_r($this->connection);
     }

    private function getProducts()
    {
        $sql = "SELECT p.product_id, pd.name  FROM " . DB_PREFIX . "product as p LEFT JOIN  " . DB_PREFIX . "product_description as pd ON (p.product_id = pd.product_id) WHERE p.status = '1' AND pd.name IS NOT NULL AND pd.name !='' ";

        return $this->connection->query($sql);
    }

    private function getCategories()
    {
        $sql = "SELECT c.category_id, cd.name  FROM " . DB_PREFIX . "category as c LEFT JOIN  " . DB_PREFIX . "category_description as cd ON (c.category_id = cd.category_id) WHERE c.status = '1' AND cd.name IS NOT NULL AND cd.name !='' ";

        return $this->connection->query($sql);
    }
    private function getManufacturer()
    {
        $sql = "SELECT m.manufacturer_id, m.name FROM " . DB_PREFIX . "manufacturer as m  WHERE m.name IS NOT NULL AND m.name !='' ";

        return $this->connection->query($sql);
    }
    private function getArticle()
    {
        $sql = "SELECT ad.article_id, ad.name FROM " . DB_PREFIX . "article_description  as ad  WHERE ad.name IS NOT NULL AND ad.name !='' ";

        return $this->connection->query($sql);
    }
    private function addSeo($query, $keyword)
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $keyword . "' OR query = '" . $query . "'";

        $check = $this->connection->query($sql);
        if (!$check->num_rows) {
            $sql = "INSERT " . DB_PREFIX . "seo_url SET store_id = '" . STORE_ID . "', language_id = '" . LANGUAGE_ID . "', query = '" . $query . "', keyword = '" . $keyword . "'";
            $this->connection->query($sql);
        }
    }

    public function start($types)
    {
        if(in_array('products',$types)){
            $products = $this->getProducts();
            while ($item = $products->fetch_assoc()) {
                $qwer = $this->str2url($item['name']);
                if ($qwer) {
                    $this->addSeo( 'product_id=' . $item['product_id'],$this->str2url($item['name']));
                }
            }
        }
        if(in_array('categories',$types)){
            $categories = $this->getCategories();
            while ($item = $categories->fetch_assoc()) {

                $qwer = $this->str2url($item['name']);
                if ($qwer) {
                    $this->addSeo( 'category_id=' . $item['category_id'],$this->str2url($item['name']));
                }
            }
        }
        if(in_array('manufacturer',$types)){
            $manufacturer = $this->getManufacturer();

            while ($item = $manufacturer->fetch_assoc()) {

                $qwer = $this->str2url($item['name']);
                if ($qwer) {
                     $this->addSeo( 'manufacturer_id=' . $item['manufacturer_id'],$this->str2url($item['name']));
                }
            }
        }
        if(in_array('article',$types)){
            $articles = $this->getArticle();

            while ($item = $articles->fetch_assoc()) {

                $qwer = $this->str2url($item['name']);
                if ($qwer) {
                    $this->addSeo( 'article_id=' . $item['article_id'],$this->str2url($item['name']));
                }
            }
        }
        if($this->debug){
           $this->debug();
        }
    }

    private function str2url($str)
    {
        $str = strtr($str, $this->converter);
        $str = strtolower($str);
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str); 
        $str = trim($str, "-");
        return $str;
    }


}
