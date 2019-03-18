<?php

namespace Orange\QuickMakingBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Orange\QuickMakingBundle\Service\Block;

class BlockExtension extends Twig_Extension {
	
    protected $block;

    public function __construct(Block $block) {
        $this->block = $block;
    }

    public function getFunctions() {
        return array(
            'block_render' => new Twig_SimpleFunction('render_block', array($this, 'renderBlock'), array('is_safe' => array('html')))
        );
    }

    public function renderBlock($template, $block, $data = array()) {
        return $this->block->render($template, $block, $data);
    }

    public function getName() {
        return 'block_extension';
    }
}