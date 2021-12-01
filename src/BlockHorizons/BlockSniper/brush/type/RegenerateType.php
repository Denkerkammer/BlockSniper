<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\brush\type;

use BlockHorizons\BlockSniper\brush\BrushProperties;
use BlockHorizons\BlockSniper\brush\Target;
use BlockHorizons\BlockSniper\brush\Type;
use BlockHorizons\BlockSniper\revert\AsyncRevert;
use BlockHorizons\BlockSniper\session\Session;
use Generator;
use pocketmine\world\format\Chunk;

/*
 * Regenerates the chunk looked at.
 */

class RegenerateType extends Type{

	/** @var Session */
	private $session;

	public function __construct(BrushProperties $properties, Target $target, Generator $blocks = null, Session $session = null){
		parent::__construct($properties, $target, $blocks);
		$this->session = $session;
	}

	/**
	 * @return Generator
	 */
	public function fill() : Generator{
		if($this->myPlotChecked){
			return;
		}
		$x = $this->target->x >> 4;
		$z = $this->target->z >> 4;

		$oldChunk = $this->chunkManager->getChunk($x, $z);
		$c = new Chunk();
		$this->chunkManager->setChunk($x, $z, $c);

		$this->chunkManager->generateChunkCallback($x, $z, $c);
		$this->chunkManager->requestChunkPopulation($x, $z, null);

		$this->session->getRevertStore()->saveUndo(new AsyncRevert([$c], [$oldChunk], $this->chunkManager));

		if(false){
			// Make PHP recognize this is a generator.
			yield;
		}
	}

	/**
	 * @return bool
	 */
	public function canBeExecutedAsynchronously() : bool{
		return false;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Chunk Regenerate";
	}

	/**
	 * @return bool
	 */
	public function canBeHollow() : bool{
		return false;
	}

	/**
	 * @return bool
	 */
	public function usesSize() : bool{
		return false;
	}

	/**
	 * @return bool
	 */
	public function usesBrushBlocks() : bool{
		return false;
	}
}