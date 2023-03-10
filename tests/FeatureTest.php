<?php

use Overtrue\Pinyin\Pinyin;

class FeatureTest extends PHPUnit_Framework_TestCase
{
    protected $pinyin;

    // test delimiter
    public function testDelimiter()
    {
        $this->assertEquals('nín hǎo', Pinyin::trans('您好'));
    }

    // test appends custom dict.
    public function testAppends()
    {
        Pinyin::appends(array('好' => 'hao1'));
        $this->assertEquals('hāo', Pinyin::trans('好'));
        $appends = array(
            '冷' => 're4',
        );
        Pinyin::appends($appends);
        Pinyin::set('accent',false);
        $this->assertEquals('re', Pinyin::trans('冷'));
        $this->assertEquals('yin yue', Pinyin::trans('音乐'));
        Pinyin::set('accent',true);
    }

    // test temporary changes delimiter
    public function testTemporaryDelimiter()
    {
        $this->assertEquals('nín-hǎo', Pinyin::trans('您好', array('delimiter' => '-')));
        Pinyin::set('delimiter', '*');
        $this->assertEquals('nín*hǎo', Pinyin::trans('您好'));
        Pinyin::set('delimiter', ' ');
        $this->assertEquals('nín hǎo', Pinyin::trans('您好'));
    }

    // test get first letter
    public function testLetters()
    {
        $this->assertEquals('n h', Pinyin::letter('您好'));
        $this->assertEquals('n-h', Pinyin::letter('您好', array('delimiter' => '-')));
        $this->assertEquals('N-H', Pinyin::letter('您好', array('delimiter' => '-', 'uppercase' => true)));
        $this->assertEquals('c q', Pinyin::letter('重庆'));
        $this->assertEquals('z y', Pinyin::letter('重要'));
        $this->assertEquals('nh', Pinyin::letter('您好', array('delimiter' => '')));
        $this->assertEquals('kxll', Pinyin::letter('康熙来了', array('delimiter' => '')));
        $this->assertEquals('A B Z Z Q Z Z Z Z', Pinyin::letter("阿坝藏族羌族自治州", array('uppercase' => true)));
        $this->assertEquals('d z x w q l x b d d z d g m h', Pinyin::letter('带着希望去旅行，比到达终点更美好'));
        $this->assertEquals('z q s l z w z w', Pinyin::letter('赵钱孙李 周吴郑王'));
        $this->assertEquals('l x', Pinyin::letter('旅行'));

        // issue #27
        $this->assertEquals('d l d n r', Pinyin::letter('独立的女人'));
        $this->assertEquals('n', Pinyin::letter('女'));
        $this->assertEquals('n r', Pinyin::letter('女人'));
    }

    // test 'parse'
    public function testParse()
    {
        $this->assertEquals(array('src' => '您好', 'pinyin' => 'nín hǎo', 'letter' => 'n h'), Pinyin::parse('您好'));
        $this->assertEquals(array('src' => '-', 'pinyin' => '-', 'letter' => ''), Pinyin::parse('-'));
        $this->assertEquals(array('src' => '', 'pinyin' => '', 'letter' => ''), Pinyin::parse(''));
    }

    // test return with tone
    public function testResultWithTone()
    {
        $this->assertEquals('dài zhe xī wàng qù lǚ xíng , bǐ dào dá zhōng diǎn gèng měi hǎo', Pinyin::trans('带着希望去旅行，比到达终点更美好'));
    }

    // test without tone
    public function testResultWithoutTone()
    {
        $this->assertEquals('dai zhe xi wang qu lu xing , bi dao da zhong dian geng mei hao', Pinyin::trans('带着希望去旅行，比到达终点更美好', array('accent' => false)));
    }

    // test user added words
    public function testAdditionalWords()
    {
        $this->assertEquals('liǎo wú shēng qù', Pinyin::trans('了无生趣'));
    }

    // test place name
    public function testPlaceName()
    {
        $this->assertEquals('běi jīng', Pinyin::trans('北京'));
        $this->assertEquals('shàng hǎi', Pinyin::trans('上海'));
        $this->assertEquals('jiāng sū', Pinyin::trans('江苏'));
        $this->assertEquals('guǎng dōng', Pinyin::trans('广东'));
        $this->assertEquals('nán jīng', Pinyin::trans('南京'));
        $this->assertEquals('hū hé hào tè', Pinyin::trans('呼和浩特'));
        $this->assertEquals('gān sù', Pinyin::trans('甘肃'));
        $this->assertEquals('níng xià', Pinyin::trans('宁夏'));
        $this->assertEquals('yún nán', Pinyin::trans('云南'));
        $this->assertEquals('hǎi nán', Pinyin::trans('海南'));
        $this->assertEquals('liáo níng', Pinyin::trans('辽宁'));
        $this->assertEquals('hēi lóng jiāng', Pinyin::trans('黑龙江'));
    }

    // test special words
    public function testSpecialWords()
    {
        $this->assertEquals('hello, world!', Pinyin::trans('hello, world!'));
        $this->assertEquals('DNA jiàn dìng', Pinyin::trans('DNA鉴定'));
        $this->assertEquals('21 sān tǐ zōng hé zhèng', Pinyin::trans('21三体综合症'));
        $this->assertEquals('C pán', Pinyin::trans('C盘'));
        $this->assertEquals('G diǎn', Pinyin::trans('G点'));
        $this->assertEquals('zhōng dù xìng fèi shuǐ zhǒng', Pinyin::trans('中度性肺水肿'));
    }

    // test Polyphone
    public function testPolyphone()
    {
        // 了
        $this->assertEquals('liǎo rán', Pinyin::trans('了然'));
        $this->assertEquals('lái le', Pinyin::trans('来了'));

        // 还
        $this->assertEquals('hái yǒu', Pinyin::trans('还有'));
        $this->assertEquals('jiāo huán', Pinyin::trans('交还'));

        // 什
        $this->assertEquals('shén me', Pinyin::trans('什么'));
        $this->assertEquals('shí jǐn', Pinyin::trans('什锦'));

        // 便
        $this->assertEquals('biàn dāng', Pinyin::trans('便当'));
        $this->assertEquals('pián yi', Pinyin::trans('便宜'));

        // 剥
        $this->assertEquals('bāo pí', Pinyin::trans('剥皮'));
        $this->assertEquals('bō pí qì', Pinyin::trans('剥皮器'));

        // 不
        $this->assertEquals('péi bú shi', Pinyin::trans('赔不是'));
        $this->assertEquals('pǎo le hé shàng , pǎo bù liǎo miào', Pinyin::trans('跑了和尚，跑不了庙'));

        // 降
        $this->assertEquals('jiàng wēn', Pinyin::trans('降温'));
        $this->assertEquals('tóu xiáng', Pinyin::trans('投降'));

        // 都
        $this->assertEquals('shǒu dū', Pinyin::trans('首都'));
        $this->assertEquals('dōu shén me nián dài le', Pinyin::trans('都什么年代了'));

        // 乐
        $this->assertEquals('kuài lè', Pinyin::trans('快乐'));
        $this->assertEquals('yīn yuè', Pinyin::trans('音乐'));

        // 长
        $this->assertEquals('chéng zhǎng', Pinyin::trans('成长'));
        $this->assertEquals('cháng jiāng', Pinyin::trans('长江'));

        // 难
        $this->assertEquals('nàn mín', Pinyin::trans('难民'));
        $this->assertEquals('nán guò', Pinyin::trans('难过'));

        // 厦
        $this->assertEquals('dà shà', Pinyin::trans('大厦'));
        $this->assertEquals('xià mén', Pinyin::trans('厦门'));
    }

    /**
     * 测试字母+数字Bug
     */
    public function testNumberWithAlpha()
    {
        $this->assertEquals('cè shì R60', Pinyin::trans("测试R60"));
        Pinyin::set('accent', false);
        $this->assertEquals('ce shi R60', Pinyin::trans("测试R60"));
        $this->assertEquals('ce ce5 shi Rr60', Pinyin::trans("测ce5试Rr60"));
        $this->assertEquals('ce shi R50', Pinyin::trans("测试R50"));
        $this->assertEquals('ce 3sh4i R50', Pinyin::trans("测3sh4i R50"));
        $this->assertEquals('ce3sh4i R50', Pinyin::trans("ce3sh4i R50"));
        $this->assertEquals('33ai4', Pinyin::trans("33ai4"));
        $this->assertEquals('33ai4 ni', Pinyin::trans("33ai4你"));
        $this->assertEquals('ai 334 ni', Pinyin::trans("爱334你"));
        $this->assertEquals('aaaa1234', Pinyin::trans("aaaa1234"));
        $this->assertEquals('aaaa_1234', Pinyin::trans("aaaa_1234"));
        $this->assertEquals('ai45 liao wu sheng qu ce shi', Pinyin::trans("ai45了无生趣测试"));
    }

    /**
     * 测试单个音的字
     *
     * bug: #19
     * bug: #22
     * bug: #23
     * bug: #24
     * bug: #29
     * bug: #235
     */
    public function testSingleAccent()
    {
        Pinyin::set('accent', true);
        $this->assertEquals('a le tai', Pinyin::trans("阿勒泰", array('accent' => false)));
        $this->assertEquals('e er duo si', Pinyin::trans("鄂尔多斯", array('accent' => false)));
        $this->assertEquals('zu', Pinyin::trans("足", array('accent' => false)));
        $this->assertEquals('feng', Pinyin::trans("冯", array('accent' => false)));
        $this->assertEquals('bao hu ping he', Pinyin::trans("暴虎冯河", array('accent' => false)));
        $this->assertEquals('hé', Pinyin::trans('和'));
        $this->assertEquals('gěi', Pinyin::trans('给'));
        $this->assertEquals('là', Pinyin::trans('腊'));
        # 28 词库不全
        $this->assertEquals('kun', Pinyin::trans('堃', array('accent' => false)));

        #29
        $this->assertEquals('dì', Pinyin::trans("地"));
        $this->assertEquals('zhi yu si di', Pinyin::trans("置于死地",  array('accent' => false)));

        #35
        $this->assertEquals('ji xiao', Pinyin::trans("技校", array('accent' => false)));
        $this->assertEquals('jiao zheng', Pinyin::trans("校正", array('accent' => false)));
    }

    /**
     * 测试只保留中文
     *
     * bug #26
     */
    public function testOnlyChinese()
    {
        $this->assertEquals('dai zhe xi wang qu', Pinyin::trans("带着希望去china", array('only_chinese' => true, 'accent' => false)));
        $this->assertEquals('d z x w q l', Pinyin::letter("带着希望去了china"));
        $this->assertEquals('d z x w q l', Pinyin::letter("带着希望去china了"));
        $this->assertEquals('d z x w q l', Pinyin::letter("带着希望去china了hello4"));
        $this->assertEquals('d z x w q l', Pinyin::letter("45china带着希望去了hello4"));
        $this->assertEquals('d z x w q l', Pinyin::letter("ci4ina带着l希望去了hello4"));
        $this->assertEquals('d z x w q l', Pinyin::letter("带着希望去了china", array('only_chinese' => true)));
        $this->assertEquals('d z x w q l', Pinyin::letter("带着le5希望56去了china", array('only_chinese' => true)));
    }

    /**
     * 混合内容
     */
    public function testFixedTest()
    {
        $this->assertEquals('java gong cheng shi', Pinyin::trans("java工程师", ['accent' => false]));
    }
}