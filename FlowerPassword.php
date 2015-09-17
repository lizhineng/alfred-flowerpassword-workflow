<?php
/**
 * Flower Password for Alfred 2.
 *
 * Flower Password is a new way to manage your passwords, no storage, much safer.
 * You can check out the official website to get more information,
 * http://www.flowerpassword.com
 *
 * @author      Li Zhineng
 * @link        http://zhineng.li
 * @version     1.0.0
 */
require_once 'workflows.php';

class FlowerPassword {

    /**
     * Store your password.
     *
     * @var string
     */
    private $password = null;

    /**
     * Store your prefix of the code.
     *
     * @var string
     */
    private $prefix = '';

    /**
     * Store your suffix of the code.
     *
     * @var string
     */
    private $suffix = '';

    /**
     * Workflows utility class
     *
     * @var object
     */
    private $workflows = null;

    public function __construct( $password ) {

        $this->setPassword( $password );

        $this->workflows = new Workflows();
    }

    /**
     * Set your password.
     *
     * @param $password string
     */
    public function setPassword( $password ) {
        $this->password = $password;
    }

    /**
     * Get your password.
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set prefix of the code.
     *
     * @param $prefix string
     */
    public function setPrefix( $prefix ) {

        $this->prefix = $prefix;
    }

    /**
     * Get prefix of the code.
     *
     * @return string
     */
    public function getPrefix() {

        return $this->prefix;
    }
    
    /**
     * Set suffix of the code
     *
     * @param $suffix string
     */
    public function setSuffix( $suffix ) {

        $this->suffix = $suffix;
    }

    /**
     * Get suffix of the code
     *
     * @return string
     */
    public function getSuffix() {

        return $this->suffix;
    }

    /**
     * Hash the code with password.
     *
     * @param $code string - the website or app's code
     * @param $length number - the bit of password
     * @return string - the hashed password
     */
    public function hash($code, $length = 16) {

        if ($this->getPassword() && $code && (1 < $length) && ($length < 33)) {

            $hmd5 = hash_hmac('md5', $this->getPassword(), $this->getPrefix() . $code . $this->getSuffix());

            $rule = str_split(hash_hmac('md5', $hmd5, "kise"));
            $source = str_split(hash_hmac('md5', $hmd5, "snow"));
            $str = "sunlovesnow1990090127xykab";

            for ($i = 0; $i < 32; $i++) {
                if (!is_numeric($source[$i])) {
                    if (stripos($str, $rule[$i]) !== false) {
                        $source[$i] = strtoupper($source[$i]);
                    }
                }
            }

            if (is_numeric($source[0])) {
                $source[0] = 'K';
            }

            $hashed = substr(implode($source), 0, $length);

            $this->workflows->result($hashed, $hashed, $hashed, $this->getPrefix() . $code . $this->getSuffix(), '', false);

        } else {

            $this->workflows->result('', '', 'Hash faild', 'Cannot hash the code that you provided', '', false);

        }


        return $this->workflows->toxml();
    }
}
