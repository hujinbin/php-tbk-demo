<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddGoodsFileInfoGetRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(List<String>, "url_list")
	*/
	private $urlList;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "url_list", $this->urlList);

	}

	public function getVersion()
	{
		return "V1";
	}

	public function getDataType()
	{
		return "JSON";
	}

	public function getType()
	{
		return "pdd.goods.file.info.get";
	}

	public function setUrlList($urlList)
	{
		$this->urlList = $urlList;
	}

}
