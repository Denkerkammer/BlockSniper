<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\brush\shape;

use BlockHorizons\BlockSniper\brush\BrushProperties;
use BlockHorizons\BlockSniper\brush\Shape;
use Generator;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;

class SphereShape extends Shape{

	/**
	 * @return Generator
	 */
	public function getVectors() : Generator{
		$radiusX = ($this->maxX - $this->minX) / 2;
		$radiusXS = (int) $radiusX === 0 ? 1 : $radiusX ** 2;
		$radiusY = ($this->maxY - $this->minY) / 2;
		$radiusYS = (int) $radiusY === 0 ? 1 : $radiusY ** 2;
		$radiusZ = ($this->maxZ - $this->minZ) / 2;
		$radiusZS = (int) $radiusZ === 0 ? 1 : $radiusZ ** 2;

		$avgRadius = ($radiusX + $radiusY + $radiusZ) / 3;
		$rSquared = $avgRadius ** 2;

		$centerX = $this->minX + $radiusX;
		$centerY = $this->minY + $radiusY;
		$centerZ = $this->minZ + $radiusZ;

		for($x = $this->maxX; $x >= $this->minX; $x--){
			$xs = ($x - $centerX) ** 2 / $radiusXS;
			for($y = $this->maxY; $y >= $this->minY; $y--){
				if($y > 255 || $y < 0){
					continue;
				}
				$ys = ($y - $centerY) ** 2 / $radiusYS;
				for($z = $this->maxZ; $z >= $this->minZ; $z--){
					$zs = ($z - $centerZ) ** 2 / $radiusZS;
					if($xs + $ys + $zs <= 1.0){
						if($this->hollow){
							if(($xs * $radiusXS + $ys * $radiusYS + $zs * $radiusZS) < $rSquared - 3 - $avgRadius * 2){
								continue;
							}
						}
						yield new Vector3((int) $x, (int) $y, (int) $z);
					}
				}
			}
		}
	}

	/**
	 * @return int
	 */
	public function getBlockCount() : int{
		$i = 0;
		$radiusX = ($this->maxX - $this->minX) / 2;
		$radiusXS = (int) $radiusX === 0 ? 1 : $radiusX ** 2;
		$radiusY = ($this->maxY - $this->minY) / 2;
		$radiusYS = (int) $radiusY === 0 ? 1 : $radiusY ** 2;
		$radiusZ = ($this->maxZ - $this->minZ) / 2;
		$radiusZS = (int) $radiusZ === 0 ? 1 : $radiusZ ** 2;

		$centerX = $this->minX + $radiusX;
		$centerY = $this->minY + $radiusY;
		$centerZ = $this->minZ + $radiusZ;

		for($x = $this->maxX; $x >= $this->minX; $x--){
			$xs = ($x - $centerX) ** 2 / $radiusXS;
			for($y = $this->maxY; $y >= $this->minY; $y--){
				$ys = ($y - $centerY) ** 2 / $radiusYS;
				for($z = $this->maxZ; $z >= $this->minZ; $z--){
					$zs = ($z - $centerZ) ** 2 / $radiusZS;
					if($xs + $ys + $zs <= 1.0){
						if($this->hollow){
							if($xs + $ys + $zs < 0.85){
								continue;
							}
						}
						++$i;
					}
				}
			}
		}

		return $i;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return $this->hollow ? "Hollow Sphere" : "Sphere";
	}

	/**
	 * @param Vector3         $center
	 * @param BrushProperties $brush
	 * @param AxisAlignedBB   $bb
	 */
	public function buildSelection(Vector3 $center, BrushProperties $brush, AxisAlignedBB $bb) : void{
		[$bb->maxX, $bb->maxY, $bb->maxZ, $bb->minX, $bb->minY, $bb->minZ] = [
			$center->x + $brush->size, $center->y + $brush->size, $center->z + $brush->size,
			$center->x - $brush->size, $center->y - $brush->size, $center->z - $brush->size
		];
	}
}
