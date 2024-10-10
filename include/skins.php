<?php

class Skin {
    public $filename;
    private static $stacks = [];
    private static $sections = [];
    private static $scanned = false;

    public function __construct($filename) {
        $this->filename = $filename;
    }

    public function make() {
        global $TMPL, $CONF, $user; // Access global TMPL
       


        // Automatically add the .blade.php extension
        $file = sprintf('./theme/html/%s.blade.php', $this->filename);
        if (!file_exists($file)) {
            throw new Exception("File not found: " . $file);
        }

        $bladeContent = file_get_contents($file);
        $parsedContent = self::parse($bladeContent);
        
        // echo "<pre>Generated PHP Code:\n" . htmlspecialchars($parsedContent) . "\n</pre>";

        /*
        ob_start();
        extract($TMPL, EXTR_SKIP); // Extract global TMPL data into variables
        eval('?>' . $parsedContent); // Execute the parsed PHP code
        return ob_get_clean();
        */
       
        try{
        ob_start();
        extract($TMPL, EXTR_SKIP); // Extract global TMPL data into variables
        eval('?>' . $parsedContent); // Execute the parsed PHP code
        $content = ob_get_clean();
        } catch(ParseError $e){
            echo 'Error in eval: ', $e->getMessage();
        }

        // echo "<pre>Output:\n" . htmlspecialchars($content) . "\n</pre>";
        return $content;
    }

    
    
    
    public static function parse($blade) {
        // Handle Blade-like comments
        $blade = preg_replace('/{{--(.*?)--}}/s', '', $blade);
        // Handle Blade-like syntax
        $blade = preg_replace('/@php\s*(.*?)\s*@endphp/s', '<?php $1; ?>', $blade);
        
        $blade = preg_replace('/@foreach\s*\(\s*\$(.*?)\s+as\s+\$(.*?)\s*\)/', '<?php foreach ($$1 as $$2): ?>', $blade);
        $blade = preg_replace('/@endforeach/', '<?php endforeach; ?>', $blade);


        $blade = preg_replace('/@for\s*\((.*?)\)/', '<?php for ($1): ?>', $blade);
        $blade = preg_replace('/@endfor/', '<?php endfor; ?>', $blade);
        $blade = preg_replace('/@empty\s*\((.*?)\)/', '<?php if (empty($1)): ?>', $blade);
        $blade = preg_replace('/@endempty/', '<?php endif; ?>', $blade);




   // Handle switch cases
    $blade = preg_replace('/@switch\s*\((.*?)\)/', '<?php switch($1): ?>', $blade);
    $blade = preg_replace('/@case\s*\((.*?)\)/', '<?php case $1: ?>', $blade);
    $blade = preg_replace('/@break/', '<?php break; ?>', $blade);
    $blade = preg_replace('/@default/', '<?php default: ?>', $blade);
    $blade = preg_replace('/@endswitch/', '<?php endswitch; ?>', $blade);
    
        

    /*
    *
    * @old if else condition 

            $blade = preg_replace('/@if\s*\((.*?)\)/', '<?php if ($1): ?>', $blade);
      
            $blade = preg_replace('/@elseif\s*\((.*?)\)/', '<?php elseif ($1): ?>', $blade);
            $blade = preg_replace('/@else/', '<?php else: ?>', $blade);
            $blade = preg_replace('/@endif/', '<?php endif; ?>', $blade);

    * @old if else condition ended
    *
    */

    // new if else condition  multi part

        $patternIf = '/@if\s*\(((?:[^()]*\((?:[^()]*\([^()]*\))*[^()]*\)|[^()]*)*)\)/';
        $patternElseIf = '/@elseif\s*\(((?:[^()]*\((?:[^()]*\([^()]*\))*[^()]*\)|[^()]*)*)\)/';

        // Convert @if(...) to <?php if (...) : 
        $blade = preg_replace_callback($patternIf, function ($matches) {
            return "<?php if ({$matches[1]}): ?>";
        }, $blade);

        // Convert @elseif(...) to <?php elseif (...) : 
        $blade = preg_replace_callback($patternElseIf, function ($matches) {
            return "<?php elseif ({$matches[1]}): ?>";
        }, $blade);

        // Convert @else to <?php else: 
        $blade = preg_replace('/@else/', '<?php else: ?>', $blade);

        // Convert @endif to <?php endif; 
        $blade = preg_replace('/@endif/', '<?php endif; ?>', $blade);


        // Handle @section and @yield
        $blade = preg_replace_callback('/@section\(\s*[\'"]([^\'"]+)[\'"]\s*\)\s*(.*?)\s*@endsection/s', function ($matches) {
            $name = trim($matches[1], "'\" ");
            self::$sections[$name] = self::parse($matches[2]);
            return ''; // Remove @section content from the output
        }, $blade);

        $blade = preg_replace_callback('/@yield\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', function ($matches) {
            $sectionName = trim($matches[1], "'\" ");
            return '<?php echo self::$sections["' . $sectionName . '"] ?? ""; ?>';
        }, $blade);



        
        
    
        $blade = preg_replace_callback('/@(include|includeif|include_once|require_once)\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', function ($matches) {
            $directive = $matches[1];
            $file = sprintf('./theme/html/%s.blade.php', $matches[2]);
    
            if (!file_exists($file)) {
                throw new Exception("File not found: " . $file);
            }
    
            // Read the included file's content and parse it
            $includedContent = file_get_contents($file);
            $parsedContent = Skin::parse($includedContent); // Recursively parse the included file
    
            // Return the parsed content wrapped in PHP directive
            switch ($directive) {
                case 'include':
                    return "<?php echo (function() { ob_start(); global \$TMPL; extract(\$TMPL, EXTR_SKIP); ?>$parsedContent<?php return ob_get_clean(); })(); ?>";
                case 'includeif':
                    return "<?php if (file_exists('$file')) echo (function() { ob_start(); global \$TMPL; extract(\$TMPL, EXTR_SKIP); ?>$parsedContent<?php return ob_get_clean(); })(); ?>";
                case 'include_once':
                    return "<?php if (!isset(\$_included_files['$file'])) { echo (function() { ob_start(); global \$TMPL; extract(\$TMPL, EXTR_SKIP); ?>$parsedContent<?php return ob_get_clean(); })(); \$_included_files['$file'] = true; } ?>";
                case 'require_once':
                    return "<?php if (!isset(\$_included_files['$file'])) { echo (function() { ob_start(); global \$TMPL; extract(\$TMPL, EXTR_SKIP); ?>$parsedContent<?php return ob_get_clean(); })(); \$_included_files['$file'] = true; } ?>";
            }
        }, $blade);



        // Handle @push and @stack
        $blade = preg_replace_callback('/@push\(\s*[\'"]([^\'"]+)[\'"]\s*\)\s*(.*?)\s*@endpush/s', function ($matches) {
            $name = trim($matches[1], "'\" ");
            $content = self::parse($matches[2]);
    
            if (!isset(self::$stacks[$name])) {
                self::$stacks[$name] = [];
            }
            self::$stacks[$name][] = $content;
            return ''; // Remove @push content from the output
        }, $blade);
    
        // Handle @stack
        $blade = preg_replace_callback('/@stack\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', function ($matches) {
            return '<?php echo implode("", self::$stacks["' . $matches[1] . '"] ?? []); ?>';
        }, $blade);
        
    


    
        // Handle @isset, @empty, @notempty and other directives
        $blade = preg_replace('/@isset\s*\((.*?)\)/', '<?php if (isset($1)): ?>', $blade);
        $blade = preg_replace('/@endisset/', '<?php endif; ?>', $blade);
        $blade = preg_replace('/@empty\s*\((.*?)\)/', '<?php if (empty($1)): ?>', $blade);
        $blade = preg_replace('/@endempty/', '<?php endif; ?>', $blade);
        $blade = preg_replace('/@notempty\s*\((.*?)\)/', '<?php if (!empty($1)): ?>', $blade);
        $blade = preg_replace('/@endnotempty/', '<?php endif; ?>', $blade);
    
    
        // Handle {{ variable }} and {!! html !!}
        $blade = self::parseBladeSyntax($blade);
    
        return $blade;
    }

    public static function getStack($name) {
        return isset(self::$stacks[$name]) ? implode('', self::$stacks[$name]) : '';
    }


    

    // Function to parse Blade syntax
    public static function parseBladeSyntax($content) {
        // Handle {!! !!} for raw HTML or PHP
        $content = preg_replace_callback('/{!!\s*(.*?)\s*!!}/', function ($matches) {
            return '<?php echo ' . $matches[1] . '; ?>';
        }, $content);
    
        // Handle {{ }} for HTML escaped output, including arrays and PHP functions
        $content = preg_replace_callback('/{{\s*(.*?)\s*}}/', function ($matches) {
            $expression = trim($matches[1]);
    
            // Handle PHP function calls or direct PHP expressions
            if (preg_match('/^(print_r|var_dump|json_encode)\s*\(/', $expression)) {
                return '<?php echo ' . $expression . '; ?>';
            } else {
                // Handle arrays and variables
                return '<?php echo htmlspecialchars(' . $expression . ', ENT_QUOTES, "UTF-8"); ?>';
            }
        }, $content);
    
        return $content;
    }
    

}
?>
