<?php 
namespace BarCodeBakery;
use App;
use BarcodeBakery\Common\BCGFontPhp;
use BarcodeBakery\Common\BCGColor;
use BarcodeBakery\Barcode\BCGcode39;
use BarcodeBakery\Common\BCGDrawing;

class BarCodeBakery {

    public $barCode;
    public $barCodeId = '';
	private $barCodePath;
	private $barCodeFont;
    public $barCodeSize;
    private $drawing;
    private $drawException;
	private $hash = '';
	
    public function __construct(){
		mt_srand();
		$this->hash = mt_rand(1000, 9999);
	}
	public function setBarCodeId($value)
	{
		$this->barCodeId = $value;
		return $this;
    }
    public function setBarCode($value)
	{
		$this->barCode = $value;
		return $this;
	}
	public function getBarCode()
	{
		return $this->barCode;
	}
	
    public function setBarCodePath($value)
	{
		$this->barCodePath = $value;
		return $this;
	}
    public function setBarCodeFont($value)
	{
		$this->barCodeFont = $value;
		return $this;
	}
    public function setBarCodeSize($barCodeSize)
	{
		$this->barCodeSize = $barCodeSize;
		return $this;
	}
	public function getBarCodeSize()
	{
		return $this->barCodeSize;
	}
	public function generateBarCode()
	{
		
		$font = new BCGFontPhp($this->barCodeFont);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		$this->drawException = null;
		try {
			$code = new BCGcode39();
			$code->setScale(1);
			$code->setThickness(18);
			$code->setForegroundColor($color_black);
			$code->setBackgroundColor($color_white);
			$code->setFont($font);
			$code->setChecksum(false);
			$code->parse($this->barCodeId);
			
		} catch (Exception $e) {
			$this->drawException = $e;
		}
	
		$filename = $this->barCodePath.'b_'. $this->barCodeId .'.png';
		
		$this->drawing = new BCGDrawing($filename, $color_white);
		if ($this->drawException) {
			$this->drawing->drawException($this->drawException);
		} else {
			$this->drawing->setBarcode($code);
			$this->drawing->draw();
		}
		$this->drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
		// App::dd($filename);
		// exit;
		return $filename;
		
    }
    
    
}