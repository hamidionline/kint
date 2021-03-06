<?php

class Kint_Renderer_Rich_SimpleXMLElement extends Kint_Renderer_Rich_Plugin
{
    public function render($o)
    {
        $children = $this->renderer->renderChildren($o);

        $header = '';

        if (($s = $o->getModifiers()) !== null) {
            $header .= '<var>'.$s.'</var> ';
        }

        if (($s = $o->getName()) !== null) {
            $header .= '<dfn>'.Kint_Object_Blob::escape($s).'</dfn> ';

            if ($s = $o->getOperator()) {
                $header .= Kint_Object_Blob::escape($s, 'ASCII').' ';
            }
        }

        if (($s = $o->getType()) !== null) {
            $s = Kint_Object_Blob::escape($s);

            if ($o->reference) {
                $s = '&amp;'.$s;
            }

            $header .= '<var>'.Kint_Object_Blob::escape($s).'</var> ';
        }

        if (($s = $o->getSize()) !== null) {
            $header .= '('.Kint_Object_Blob::escape($s).') ';
        }

        if ($s === null && $c = $o->getRepresentation('contents')) {
            $c = reset($c->contents);

            if ($c && ($s = $c->getValueShort()) !== null) {
                if (Kint_Renderer_Rich::$strlen_max && Kint_Object_Blob::strlen($s) > Kint_Renderer_Rich::$strlen_max) {
                    $s = substr($s, 0, Kint_Renderer_Rich::$strlen_max).'...';
                }
                $header .= Kint_Object_Blob::escape($s);
            }
        }

        $header = $this->renderer->renderHeaderWrapper($o, (bool) strlen($children), $header);

        return '<dl>'.$header.$children.'</dl>';
    }
}
