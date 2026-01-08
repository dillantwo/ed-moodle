<?php

class __Mustache_9481fc6412fac85cb626004c2a1397e0 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $value = $context->find('aiusagedata');
        $buffer .= $this->section68b0a37fbb82c5eaf645e65d539949d7($context, $indent, $value);
        $value = $context->find('aiusagedata');
        if (empty($value)) {
            
            $buffer .= $indent . '    ';
            $value = $context->find('str');
            $buffer .= $this->sectionB4338d0ef195a085f2bca60635a83cd5($context, $indent, $value);
            $buffer .= '
';
        }

        return $buffer;
    }

    private function section7b8dafe3ee2718c915ff71eb41ff0445(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' aiusagedata, hub ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= ' aiusagedata, hub ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionF0a82f69d3a145943c3c2d06c49d7b69(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                                <li>{{values}}</li>
                            ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                                <li>';
                $value = $this->resolveValue($context->find('values'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '</li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section229fe2b3114805af301c0dd0a02da7ca(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                                    <ul>
                                        <li>{{.}}</li>
                                    </ul>
                                ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                                    <ul>
';
                $buffer .= $indent . '                                        <li>';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '</li>
';
                $buffer .= $indent . '                                    </ul>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD5b439f3d626d00ebce643321f25e558(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        <ul>
                            {{#values}}
                                <li>{{values}}</li>
                            {{/values}}

                            {{^values}}
                                <li>{{label}}:</li>
                                {{#models}}
                                    <ul>
                                        <li>{{.}}</li>
                                    </ul>
                                {{/models}}
                            {{/values}}
                        </ul>
                    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                        <ul>
';
                $value = $context->find('values');
                $buffer .= $this->sectionF0a82f69d3a145943c3c2d06c49d7b69($context, $indent, $value);
                $buffer .= $indent . '
';
                $value = $context->find('values');
                if (empty($value)) {
                    
                    $buffer .= $indent . '                                <li>';
                    $value = $this->resolveValue($context->find('label'), $context);
                    $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                    $buffer .= ':</li>
';
                    $value = $context->find('models');
                    $buffer .= $this->section229fe2b3114805af301c0dd0a02da7ca($context, $indent, $value);
                }
                $buffer .= $indent . '                        </ul>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7c0f28d03070cd0adaff71e02579c1db(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <ul>
                    <li>{{actionname}}:</li>
                    {{#aiactionvalues}}
                        <ul>
                            {{#values}}
                                <li>{{values}}</li>
                            {{/values}}

                            {{^values}}
                                <li>{{label}}:</li>
                                {{#models}}
                                    <ul>
                                        <li>{{.}}</li>
                                    </ul>
                                {{/models}}
                            {{/values}}
                        </ul>
                    {{/aiactionvalues}}
                </ul>
            ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                <ul>
';
                $buffer .= $indent . '                    <li>';
                $value = $this->resolveValue($context->find('actionname'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= ':</li>
';
                $value = $context->find('aiactionvalues');
                $buffer .= $this->sectionD5b439f3d626d00ebce643321f25e558($context, $indent, $value);
                $buffer .= $indent . '                </ul>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section60fc2510f052aae199544461928c72e5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <li>{{providername}}:</li>
            {{#aiactions}}
                <ul>
                    <li>{{actionname}}:</li>
                    {{#aiactionvalues}}
                        <ul>
                            {{#values}}
                                <li>{{values}}</li>
                            {{/values}}

                            {{^values}}
                                <li>{{label}}:</li>
                                {{#models}}
                                    <ul>
                                        <li>{{.}}</li>
                                    </ul>
                                {{/models}}
                            {{/values}}
                        </ul>
                    {{/aiactionvalues}}
                </ul>
            {{/aiactions}}
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <li>';
                $value = $this->resolveValue($context->find('providername'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= ':</li>
';
                $value = $context->find('aiactions');
                $buffer .= $this->section7c0f28d03070cd0adaff71e02579c1db($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section881b9339eb5dc3c469a3e0368b81a7c6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <ul>
                    <li>{{.}}</li>
                </ul>
            ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                <ul>
';
                $buffer .= $indent . '                    <li>';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '</li>
';
                $buffer .= $indent . '                </ul>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section994f4fe20dd75d6aa513d2ff0182cbcd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <li>{{label}}:</li>
            {{#values}}
                <ul>
                    <li>{{.}}</li>
                </ul>
            {{/values}}
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <li>';
                $value = $this->resolveValue($context->find('label'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= ':</li>
';
                $value = $context->find('values');
                $buffer .= $this->section881b9339eb5dc3c469a3e0368b81a7c6($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section68b0a37fbb82c5eaf645e65d539949d7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    {{#str}} aiusagedata, hub {{/str}}:
    <ul>
        {{! AI providers. }}
        {{#providers}}
            <li>{{providername}}:</li>
            {{#aiactions}}
                <ul>
                    <li>{{actionname}}:</li>
                    {{#aiactionvalues}}
                        <ul>
                            {{#values}}
                                <li>{{values}}</li>
                            {{/values}}

                            {{^values}}
                                <li>{{label}}:</li>
                                {{#models}}
                                    <ul>
                                        <li>{{.}}</li>
                                    </ul>
                                {{/models}}
                            {{/values}}
                        </ul>
                    {{/aiactionvalues}}
                </ul>
            {{/aiactions}}
        {{/providers}}

        {{! Time range for stats. }}
        {{#timerange}}
            <li>{{label}}:</li>
            {{#values}}
                <ul>
                    <li>{{.}}</li>
                </ul>
            {{/values}}
        {{/timerange}}
    </ul>
';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '    ';
                $value = $context->find('str');
                $buffer .= $this->section7b8dafe3ee2718c915ff71eb41ff0445($context, $indent, $value);
                $buffer .= ':
';
                $buffer .= $indent . '    <ul>
';
                $value = $context->find('providers');
                $buffer .= $this->section60fc2510f052aae199544461928c72e5($context, $indent, $value);
                $buffer .= $indent . '
';
                $value = $context->find('timerange');
                $buffer .= $this->section994f4fe20dd75d6aa513d2ff0182cbcd($context, $indent, $value);
                $buffer .= $indent . '    </ul>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB4338d0ef195a085f2bca60635a83cd5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' noaiusagedata, hub ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= ' noaiusagedata, hub ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
