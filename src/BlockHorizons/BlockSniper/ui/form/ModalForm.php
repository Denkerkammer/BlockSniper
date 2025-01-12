<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\ui\form;

use pocketmine\form\Form as FormInterface;
use pocketmine\player\Player;

class ModalForm extends Form implements FormInterface{

	/** @var callable */
	private $yes, $no;

	public function __construct(string $title, string $content){
		$this->data = [
			"type" => "modal",
			"title" => $title,
			"content" => $content,
			"button1" => "gui.yes",
			"button2" => "gui.no"
		];
		$this->yes = $this->no = function(){
		};
	}

	/**
	 * @param callable $c
	 * @param string   $text
	 */
	public function setYes(callable $c, string $text = "gui.yes"){
		$this->yes = $c;
		$this->data["button1"] = $text;
	}

	/**
	 * @param callable $c
	 * @param string   $text
	 */
	public function setNo(callable $c, string $text = "gui.no"){
		$this->no = $c;
		$this->data["button2"] = $text;
	}

	/**
	 * @param Player $player
	 * @param bool   $data
	 */
	public function handleResponse(Player $player, $data) : void{
		if($data){
			$callable = $this->yes;
		}else{
			$callable = $this->no;
		}
		$callable($player);
		if($this->responseForm !== null){
			$player->sendForm($this->responseForm);
		}
	}
}