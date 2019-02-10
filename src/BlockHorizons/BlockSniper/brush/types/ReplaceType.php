<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\brush\types;

use BlockHorizons\BlockSniper\brush\BaseType;
use BlockHorizons\BlockSniper\brush\Brush;
use pocketmine\level\ChunkManager;

/*
 * Replaces the obsolete blocks within the brush radius.
 */

class ReplaceType extends BaseType{

	public const ID = self::TYPE_REPLACE;

	public function __construct(Brush $brush, ChunkManager $level, \Generator $blocks = null){
		parent::__construct($brush, $level, $blocks);
		$this->obsolete = $brush->getObsolete();
	}

	/**
	 * @return \Generator
	 */
	public function fill() : \Generator{
		foreach($this->blocks as $block){
			foreach($this->obsolete as $obsolete){
				if($block->getId() === $obsolete->getId() and $block->getDamage() === $obsolete->getDamage()){
					yield $block;
					$this->putBlock($block, $this->randomBrushBlock());
					break;
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Replace";
	}

	/**
	 * Returns the obsolete blocks of this type.
	 *
	 * @return \Generator
	 */
	public function getObsolete() : \Generator{
		return $this->obsolete;
	}
}

