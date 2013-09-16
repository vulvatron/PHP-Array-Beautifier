<?php
/*
 * Tool - PHP Array Beautifier 
 *
 * Copy a print_r() or var_dump() string into the beautifier and clean it up, indent,
 * and add color codings for easy readability.
 *   
 * @author          Phil Harmon
 * @contributors    Olivier Lemire
 * @date            2013.09.16
 *
 */

class Debug {
    var $indent_size;
    var $colors = array(
        "Teal",
        "YellowGreen",
        "Tomato",
        "Navy",
        "MidnightBlue",
        "FireBrick",
        "DarkGreen"
    );

    /*
     * __construct
     *
     */
    public function __construct($ident=20) {
        $this->indent_size = $ident;
    }

    /*
     * _process 
     *
     * Internal function that takes an array and format it to style in HTML.
     *   
     * @author          Phil Harmon
     * @contributors    Olivier Lemire
     * @access      private
     * @param       mixed   string  Data as string
     *                      array   Data ar real array
     * @return      null
     *
     */
    private function _process($val) {
        $do_nothing = true;
        
        // Get string structure
        if(is_array($val)) {
            $val = print_r($val, true);
        }
        
        // Color counter
        $current = 0;
        
        // Split the string into character array
        $array = preg_split('//', $val, -1, PREG_SPLIT_NO_EMPTY);

        foreach($array as $char) {
            if($char == "[")
                if(!$do_nothing)
                    echo "</div>";
                else $do_nothing = false;
            if($char == "[")
                echo "<div>";
            if($char == ")") {
                echo "</div></div>";
                $current--;
            }
            
            echo $char;
            
            if($char == "(") {
                echo "<div class='indent' style='padding-left: {$this->indent_size}px; color: ".($this->colors[$current % count($this->colors)]).";'>";
                $do_nothing = true;
                $current++;
            }
        }
    }

    /*
     * get_html 
     *
     * Public function that takes input array and output the formated HTML
     *   
     * @author      Olivier Lemire
     * @access      public
     * @param       mixed   string  Data as string
     *                      array   Data ar real array
     * @return      string  Formated
     *
     */
    public function get_html($val) {
        ob_start();
        $this->_process($val);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /*
     * print_html 
     *
     * Public function that takes input array and print it directly to the user screen
     *   
     * @author      Olivier Lemire
     * @access      public
     * @param       mixed   string  string array
     *                      array   php array
     * @return      null
     *
     */
    public function print_html($val) {
        $this->_process($val);
    }
}

if ($postdata = $_POST['to_convert']) {
    $d = new Debug();
}
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>PHP-Array-Beautifier</title>
</head>
<body>
    <article>
        <header>
            <h1>PHP-Array-Beautifier</h1>
        </header>
        <div class="content">
            <p>This simple tool takes an array or object output in PHP, such as a print_r() statement and formats it in color coding to easily read your data.</div><br />

            <form action="<?php echo basename(__FILE__); ?>" method="POST">
                <div>Code to Transform:</div>
                <div><textarea id='to_conv' name='to_convert' rows='14' cols='120'><?php echo $postdata; ?></textarea></div>
                <div><input type='submit' value='Beautify' /> <span onClick="document.getElementById('to_conv').value = 'Array([mode] => sets[sets] => Array([0] => 123[1] => 456[2] => 789)[etc] => Array([letters] => Array([0] => a[1] => b)[0] => pie[1] => sharks))';" style="color: blue; cursor: pointer;">Example</a></div>
            </form>

            <?php if ($postdata): ?>
                <div><a href="<?php echo basename(__FILE__); ?>">Go back</a></div>
                <div style="width: 940px; overflow: auto;"><?php $d->print_html($postdata); ?></div>
            <?php endif; ?>
	   </div>
       <footer>
        <p>Based on <a href="http://phillihp.com/toolz/php-array-beautifier/">Phillihp Harmon</a>'s script</p>
       </footer>
    </article>
</body>
</html>
