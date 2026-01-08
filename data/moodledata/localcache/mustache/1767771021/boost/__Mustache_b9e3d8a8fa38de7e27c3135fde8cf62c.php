<?php

class __Mustache_b9e3d8a8fa38de7e27c3135fde8cf62c extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<form action="';
        $value = $this->resolveValue($context->find('url'), $context);
        $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
        $buffer .= '" method="POST" id="autopostme">
';
        $value = $context->find('params');
        $buffer .= $this->section476e36f0f5dfb4348f397b431005c419($context, $indent, $value);
        $buffer .= $indent . '    <input type="hidden" name="repost" value="true">
';
        $buffer .= $indent . '</form>
';
        $buffer .= $indent . '
';
        $value = $context->find('js');
        $buffer .= $this->section9fa51f6f6528f31da9f229f298ad55d2($context, $indent, $value);

        return $buffer;
    }

    private function section476e36f0f5dfb4348f397b431005c419(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <input type="hidden" name="{{key}}" value="{{value}}">
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '    <input type="hidden" name="';
                $value = $this->resolveValue($context->find('key'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '" value="';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '">
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9fa51f6f6528f31da9f229f298ad55d2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        document.getElementById("autopostme").submit();
';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        document.getElementById("autopostme").submit();
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
