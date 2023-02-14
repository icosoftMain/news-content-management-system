<?php namespace FLY\DOM;
      use FLY\MVC\View;
/**
 * @author K.B Brew <flyartisan@gmail.com>
 * @package process
 */

abstract class Application extends \DOMDocument {

    private static $dom;

    private $root_tag = "";

    public function __construct($root_tag="")
    {
        try {
            if(!View::_fmlIsActive()) {
                $message   = "Error: View is set to template mode. Call to FML ";
                $message .= " the FML class is unexpected. ";
                throw new \Exception($message);
            }
            
            parent::__construct();
            
            if(empty(self::$dom))
                self::$dom = $this;
            $this->root_tag = $root_tag;
            $this->formatOutput = true;
        } catch(\Exception $err) {
            echo "<br>".$err->getMessage()."<br>";
            echo "Error Occured at line ". $err->getTrace()[0]['line']."<br>";
            echo "Error Located at file ". $err->getTraceAsString();
            die("");
        } 
    }

    public function _($wave_var) 
    {
        $var = '{# $#fmllcb;'."'".$wave_var."'".'#fmlrcb; #}';

        if($wave_var === '@CSRF') {
            $var = '{# '.$wave_var.' #}';            
        }
        return $var;
    }

    public function getDOM()
    {
        return self::_document();
    }

    protected static function _document()
    {
        return self::$dom;
    }

    public function __get(string $value)
    {
        $value = \strtolower($value);
        return $this->setNodeType($this->selectElements($value));
    }


    private function selectElements(string $value)
    {
       $elements = Elements::get();
       if(array_key_exists($value, $elements)){
           return $elements[$value];
       }
       return $value;
    }

    public function root_element_exists() 
    { 
        return !empty($this->root_tag);
    }

    public function get_root_element()
    {
        $element = $this->root_tag;
        $this->root_tag = "";
        return $element;
    }

    public function setNodeType($type)
    {
        return $this->strictClass($type);
    }

    public final function tag(string $tagName)
    {
        $doc = $this->setNodeType($tagName);
        $self = $this;
        return function(array $props) use ($self, $doc, $tagName) {
            $self->setProps($doc,$props,$tagName);
            return $doc; 
        };
    }

    private function setProps($docElement, array $attributes,$tagName) {
        if(empty($attributes)) return null;

        foreach($attributes as $key => $value) {
            switch($key) {
                case 'style': 
                    if(is_array($value)) {
                        $this->setStyle($docElement,$value);
                        continue 2;
                    }
                    throw new \Exception('Style props must be set to an array');
                break;
                case 'text': case 'addtext':
                    $docElement->setText($value);
                    continue 2;                    
                break;
    

                case 'children':
                    $docElement->addChildren($attributes[$key]);
                    continue 2;                    
                break;
                case 'child':
                    $docElement->addChild($attributes[$key]);
                    continue 2;
                break;   
                default:
                    $docElement->addProperty($key, $value);
                break;
            }
        }
    }

    private function setStyle($docElement, array $css)
    {
        if(!empty($css)) {
            $stylesheet = "";
            foreach($css as $key => $value) {
                $stylesheet .= $key.": ".$value.";";
            }
            $docElement->addProperty('style',$stylesheet);
        }
    }

    private final function strictClass($type) 
    {
        return new class($type) extends Application {

            private $_currentElement = NULL;

            public function __construct($type)
            {
                parent::__construct();
                $this->formatOutput = TRUE;
                $type = strtolower($type);

                switch($type) {
                    case 'fragment':
                        $this->_currentElement = self::_document()->createDocumentFragment();
                    break;
                    default:
                        $this->_currentElement = self::_document()->createElement($type);
                        $this->_currentElement = self::_document()->appendChild($this->_currentElement);    
                    break;
                }
            }

            public function setText($text)
            {
                try {
                    $textChild = self::_document()->createTextNode(!is_string($text) ? (string)($text): $text);
                    $this->_currentElement->appendChild($textChild);
                } catch(\Exception $err) {
                    echo "Error: ". $err->getMessage()."<br>";
                    echo "Error Occured at Line ". $err->getTrace()[0]['line']."<br>";
                    echo "Error Located at file ". $err->getTraceAsString();
                    die("");
                }
                
            }

            public function addBreak()
            {
                $break = self::_document()->createElement('br');
                $this->_currentElement->appendChild($break);
            }
        
            public function addClass($classNames) 
            {
                $this->_currentElement->setAttribute('class',$classNames);
            }

            public function addId($idName)
            {
                $this->_currentElement->setAttribute('id',$idName);
            }

            public function addProperties(array $props) 
            {
                foreach($props as $key => $value) {
                    $this->_currentElement->setAttribute(
                        $key, 
                        preg_replace('/\\"|"/','&fmlqt;',!is_array($value) ? $value: json_encode($value))
                    );
                }
            }

            public function addProperty($key, $value) 
            {
                $value = !is_array($value) ? $value: json_encode($value);
                $this->_currentElement->setAttribute($key, 
                    preg_replace('/\\"|"/','&fmlqt;',
                    !is_array($value) ? $value: json_encode($value))
                );                
            }

            public function addChild($childElement)
            {
                $childElement = !is_callable($childElement) ? $childElement: $childElement([]);
                if(is_subclass_of($childElement,Application::class) && method_exists($childElement,'render')) {
                    $childElement = $childElement->render()->get_widget();
                }

                $this->_currentElement->appendChild($childElement->getElement());
            }

            public function addChildren(array $childElements)
            {
                foreach($childElements as $element) {
                    if(is_array($element)) {
                        foreach($element as $el) {
                            $this->arrangeChildren($el);
                        }
                    } else $this->arrangeChildren($element);
                }
                
            }


            private function arrangeChildren($element)
            {
                $current_child_element = !is_callable($element) ? $element: $element([]);
                   
                if(is_array($element)) $this->addChildren($element);
                else {
                    if(is_subclass_of($element,Application::class) && method_exists($element,'render')) {
                        $current_child_element = $element->render()->get_widget();
                    }
                    if(is_string($current_child_element)) {
                        $this->setText($current_child_element);
                    }
                    else $this->_currentElement->appendChild($current_child_element->getElement());
                }
            }

            public function getElement()
            {
                return $this->_currentElement;
            }
        };
    }
}