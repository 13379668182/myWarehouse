<?php
//图片水印、缩放类
class Image
{
    //保存的路径
    protected $path;
    //是否启用随机的名字
    protected $isRandName;
    //要保存的图像类型
    protected $type;


    // 通过构造方法对成员属性进行初始化
    function __construct($path = './',$isRandName = true,$type = 'png')
    {
        $this->path = $path;
        $this->isRandName = $isRandName;
        $this->type = $type;
    }

    //对外公开的水印方法
    //image 源图片
    //$water 水印图片
    //$postion 水印图片的位置
    //$tmd 水印图片的透明度
    //$prefix 水印图片前缀
    function water($image,$water,postion,$tmd = 100,$prefix = 'water_')
    {
        //1、判断这两个图片是否存在
        if ((!file_exists($image)) || (!file_exists($water))) {
            die('图片资源不存在');
        }
        //2、得到源图片与水印图片的宽度和高度以及水印图片的宽度和高度
        $imageInof = self::getImageInfo($image);
        $waterInfo = self::getImageInfo($water);
        //3、判断水印图片能否贴上来
        //4、打开图片
        //5、根据水印图片的位子计算水印图片的坐标
        //6、将水印图片贴过来
        //7、得到药品保存图片的文件名
        //8、得到保存图片的路径
        //9、保存图片
        //10、销毁资源
    }

    //对外公开的缩放方法
    function suofang()
    {

    }

    //静态方法 根据图片的路径得到图片的信息、宽度、高度、mime类型
    static function getImageInfo($imagePath)
    {
        //得到图片信息
        $info = getimagesize($imagePath);
        //保存图片的宽度、高度、mime类型
        $data['width'] = $info[0];
        $data['height'] = $info[1];
        $data['mime'] = $info['mime'];
        //将图片的信息返回
        return $data;
    }
}