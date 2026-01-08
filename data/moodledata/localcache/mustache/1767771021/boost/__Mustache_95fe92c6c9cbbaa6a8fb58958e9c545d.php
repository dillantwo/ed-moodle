<?php

class __Mustache_95fe92c6c9cbbaa6a8fb58958e9c545d extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $value = $context->find('id');
        if (empty($value)) {
            
            $buffer .= $indent . '    <div class="accordion modltitree" id="accordion">
';
        }
        $value = $context->find('nodes');
        $buffer .= $this->section9748056c5b8c9b0ba873f44215600abe($context, $indent, $value);
        $value = $context->find('id');
        if (empty($value)) {
            
            $buffer .= $indent . '    </div>
';
        }

        return $buffer;
    }

    private function section7af787ad7a8e3d1bca18178be07aea7a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="accordion-group">
            <div class="accordion-heading">
                <input type="checkbox" class="lticoursecategories" id="cat-{{{id}}}" name="lticoursecategories" value="{{{id}}}">
                <label for="cat-{{{id}}}">
                    <a class="accordion-toggle collapsed" data-bs-toggle="collapse" data-bs-parent="#accordion{{{id}}}" href="#collapse{{{id}}}" aria-expanded="true">
                        <span>{{{name}}}</span>
                    </a>
                </label>
            </div>
            <div id="collapse{{{id}}}" class="accordion-body collapse">
                <div class="accordion-inner">
                    <div class="accordion" id="accordion{{{id}}}">
                        {{>mod_lti/categorynode}}
                    </div>
                </div>
            </div>
        </div>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        <div class="accordion-group">
';
                $buffer .= $indent . '            <div class="accordion-heading">
';
                $buffer .= $indent . '                <input type="checkbox" class="lticoursecategories" id="cat-';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '" name="lticoursecategories" value="';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '">
';
                $buffer .= $indent . '                <label for="cat-';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '">
';
                $buffer .= $indent . '                    <a class="accordion-toggle collapsed" data-bs-toggle="collapse" data-bs-parent="#accordion';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '" href="#collapse';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '" aria-expanded="true">
';
                $buffer .= $indent . '                        <span>';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '</span>
';
                $buffer .= $indent . '                    </a>
';
                $buffer .= $indent . '                </label>
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '            <div id="collapse';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '" class="accordion-body collapse">
';
                $buffer .= $indent . '                <div class="accordion-inner">
';
                $buffer .= $indent . '                    <div class="accordion" id="accordion';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '">
';
                if ($partial = $this->mustache->loadPartial('mod_lti/categorynode')) {
                    $buffer .= $partial->renderInternal($context, $indent . '                        ');
                }
                $buffer .= $indent . '                    </div>
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9748056c5b8c9b0ba873f44215600abe(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        {{#haschildren}}
        <div class="accordion-group">
            <div class="accordion-heading">
                <input type="checkbox" class="lticoursecategories" id="cat-{{{id}}}" name="lticoursecategories" value="{{{id}}}">
                <label for="cat-{{{id}}}">
                    <a class="accordion-toggle collapsed" data-bs-toggle="collapse" data-bs-parent="#accordion{{{id}}}" href="#collapse{{{id}}}" aria-expanded="true">
                        <span>{{{name}}}</span>
                    </a>
                </label>
            </div>
            <div id="collapse{{{id}}}" class="accordion-body collapse">
                <div class="accordion-inner">
                    <div class="accordion" id="accordion{{{id}}}">
                        {{>mod_lti/categorynode}}
                    </div>
                </div>
            </div>
        </div>
        {{/haschildren}}
        {{^haschildren}}
        <p>
            <label for="cat-{{{id}}}">
            <input type="checkbox" class="lticoursecategories" id="cat-{{{id}}}" name="lticoursecategories" value="{{{id}}}">
                <span>{{{name}}}</span>
            </label>
        </p>
        {{/haschildren}}
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $value = $context->find('haschildren');
                $buffer .= $this->section7af787ad7a8e3d1bca18178be07aea7a($context, $indent, $value);
                $value = $context->find('haschildren');
                if (empty($value)) {
                    
                    $buffer .= $indent . '        <p>
';
                    $buffer .= $indent . '            <label for="cat-';
                    $value = $this->resolveValue($context->find('id'), $context);
                    $buffer .= ($value === null ? '' : $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '            <input type="checkbox" class="lticoursecategories" id="cat-';
                    $value = $this->resolveValue($context->find('id'), $context);
                    $buffer .= ($value === null ? '' : $value);
                    $buffer .= '" name="lticoursecategories" value="';
                    $value = $this->resolveValue($context->find('id'), $context);
                    $buffer .= ($value === null ? '' : $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '                <span>';
                    $value = $this->resolveValue($context->find('name'), $context);
                    $buffer .= ($value === null ? '' : $value);
                    $buffer .= '</span>
';
                    $buffer .= $indent . '            </label>
';
                    $buffer .= $indent . '        </p>
';
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
