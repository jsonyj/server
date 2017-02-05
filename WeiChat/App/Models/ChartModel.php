<?php

class ChartModel extends AppModel {
    /**
     * 查询指定学生和时间范围数据，$date格式为 yyyy-mm-dd xx:xx:xx
     */
    function getDetectionList($studentId, $start, $end) {
        
        $sql = "SELECT *
            FROM tb_student_detection
            WHERE tb_student_detection.student_id = $studentId 
            AND tb_student_detection.lastest=1 
            AND tb_student_detection.deleted = 0 
            AND tb_student_detection.created >= '{$start}' 
            AND tb_student_detection.created <= '{$end}' 
            ORDER BY tb_student_detection.created";

        $rs = $this->getAll($sql);
        
        $detectionList = array();
        foreach ($rs as $detection) {
            $created = date('Y-m-d', strtotime($detection['created']));//获取创建数据的时间
            $detectionList[$created] = $detection;//以当天的时间为键名，当天的数据为键值
        }
        
        $interval = (strtotime($end) - strtotime($start))/(24*60*60);
        $dataList = array();
        for ($i = 0; $i <= $interval; $i++) {
            $date = date('Y-m-d', strtotime($start) + $i * 24 * 60 * 60);
            $dataList[$date] = isset($detectionList[$date]) ? $detectionList[$date] : array();//给每个赋空值
        }
        
        return $dataList;
    }
    /**
     * @desc  身高体重月报
     * @author   wei
     */ 
    function getDetectionMonthList($id, $start, $end){
        $this->escape($id);
        $this->escape($start);
        $this->escape($end);
        
        $sql = "SELECT *, DATE_FORMAT(created,'%Y-%m-%d') as ym FROM tb_student_detection WHERE student_id = '{$id}'   AND deleted = 0 ";
        
        if ($start && $end){
            $start_time = date('Y-m-d', strtotime($start));
            $end_time = date('Y-m-d', strtotime($end));
            
             $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d') >= '{$start_time}' AND DATE_FORMAT(created,'%Y-%m-%d') <= '{$end_time}' ORDER BY created ";
            
        }else{
            $start_time = date('Y-m-d', strtotime($start));
            
            $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d')= '{$start_time}' ORDER BY created DESC  LIMIT 1";
            
        }
        $sqls = "SELECT a.* FROM ({$sql}) AS a GROUP BY a.ym";
        
        $rs = $this->getAll($sqls);
        $detectionList = array();
        foreach ($rs as $detection) {
            $created = date('Y-m-d', strtotime($detection['created']));//获取创建数据的时间
            $detectionList[$created] = $detection;//以当天的时间为键名，当天的数据为键值
        }
        
        $interval = floor((strtotime($date_end) - strtotime($date_start))/(24*60*60));
        $dataList = array();
        for ($i = 0; $i <= $interval; $i++) {
            $date = date('Y-m-d', strtotime($date_start) + $i * 24 * 60 * 60);
            $dataList[$date] = isset($detectionList[$date]) ? $detectionList[$date] : array();//给每个赋空值
        }
        return $dataList;
        
    }
    /**
     * @desc  身高体重年报
     * @author   wei
     */ 
    function getDetectionYearList($id, $date_start, $date_end){
        $this->escape($id);
        $this->escape($date_start);
        $this->escape($date_end);
        
        $sql = "SELECT *, DATE_FORMAT(created,'%Y-%m') as ym FROM tb_student_detection WHERE student_id = '{$id}'   AND deleted = 0 ";
        
        if ($date_start && $date_end){
            $start_time = date('Y-m', strtotime($date_start));
            $end_time = date('Y-m', strtotime($date_end));
            
             $sql .= " AND DATE_FORMAT(created,'%Y-%m') >= '{$start_time}' AND DATE_FORMAT(created,'%Y-%m') <= '{$end_time}'   ORDER BY id  DESC ";
            
        }else{
            $start_time = date('Y-m', strtotime($date_start));
            
            $sql .= " AND DATE_FORMAT(created,'%Y-%m')= '{$start_time}' ORDER BY created DESC  LIMIT 1";
            
        }
        $sqls = "SELECT a.* FROM ({$sql}) AS a GROUP BY a.ym";
        
        $rs = $this->getAll($sqls);
        $detectionList = array();
        foreach ($rs as $detection) {
            $created = date('Y-m', strtotime($detection['created']));//获取创建数据的时间
            $detectionList[$created] = $detection;//以当天的时间为键名，当天的数据为键值
        }
        
        $interval = floor((strtotime($date_end) - strtotime($date_start))/(24*60*60*30));
        $dataList = array();
        for ($i = 0; $i <= $interval; $i++) {
            $date = date('Y-m', strtotime($date_start) + $i * 24 * 60 * 60*30);
            $dataList[$date] = isset($detectionList[$date]) ? $detectionList[$date] : array();//给每个赋空值
        }
        return $dataList;
    }
    
    /**
     * @desc  身高体重总季报
     * @author   wei
     */ 
    function getDetectionQuarterList($id){
        $this->escape($id);
        
        $sql = "SELECT a.* FROM
                (
                    SELECT sd.*, DATE_FORMAT(sd.created,'%Y-%m-%d') AS ym FROM tb_student_detection as sd
                    WHERE sd.student_id = {$id} AND sd.deleted = 0 
                    ORDER BY created DESC
                ) AS a 
                GROUP BY a.ym;
                ";
        
        $rs = $this->getAll($sql);
        
        $detectionList = array();
        $dataList = array();
        for ($i = 0; $i <= count($rs); $i=ceil($i+count($rs)/12)) {
            $dataList[] = $rs[$i];
        }
        foreach ($dataList as $detection) {
            $created = date('Y-m-d', strtotime($detection['created']));//获取创建数据的时间
            $detectionList[$created] = $detection;//以当天的时间为键名，当天的数据为键值
        }
        return $detectionList;
    } 
    /**
     * @desc  图片
     * @author   wei
     */ 
    function getDetectionImgList($id, $date_start ,$date_end){
        $this->escape($id);
        $this->escape($date_start);
        $this->escape($date_end);
        
        $sql = "SELECT * FROM tb_file WHERE usage_id = '{$id}' AND deleted = 0 AND usage_type =" . FILE_USAGE_TYPE_STUDENT_DETECTION;
        
        if ($date_start && $date_end){
            $start_time = date('Y-m-d', strtotime($date_start));
            $end_time = date('Y-m-d', strtotime($date_end));
            
             $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d') >= '{$start_time}' AND DATE_FORMAT(created,'%Y-%m-%d') <= '{$end_time}' ";
            
        }else{
            $start_time = date('Y-m-d', strtotime($date_start));
            
            $sql .= " AND DATE_FORMAT(created,'%Y-%m-%d')= '{$start_time}' ORDER BY created DESC  LIMIT 1";
            
        }
        
        return $this->getAll($sql);
        
    }

    /**
     * 根据输入的数据输出图片
     */
    function genWeekTemperatureChart($data, $width=700, $height=230) {
        
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pData.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pDraw.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pImage.class.php');

        $arr = array(1 => "周一", 2 => "周二", 3 =>"周三", 4 => "周四", 5 => "周五", 6 => "周六", 7 => "周日");
        foreach ($data as $k => $v) {
            if (empty($v)){
                $temperatureList[] = VOID;
            } else {
                $temperatureList[] = $v['temperature'];
            }
            $dateList[] = $arr[date("N", strtotime($k))];//把最近7天对应换成 '星期X'
        }
        
        $myData = new pData();
        
        $myData->setAxisUnit(0, '℃');

        $myData->AddPoints($temperatureList, "Probe 1");
        $myData->setSerieWeight("Probe 1", 1);
        
        $serieSetings = array("R"=>0, "G"=>0, "B"=>0, "Alpha"=>20);
        $myData->setPalette('Temperatures', $serieSetings);
        
        $myData->addPoints($dateList, "Labels");
        $myData->setSerieDescription("Labels", "D");
        $myData->setAbscissa("Labels");
        
        $myPicture->Antialias = true;
        
        $myPicture = new pImage($width, $height, $myData);
        
        $Settings = array("R" => 255, "G" => 255, "B" => 255, "Dash" => 0, "DashR" => 255, "DashG" => 255, "DashB" => 255);
        $myPicture->drawFilledRectangle(0, 0, $width, $height, $Settings);
        
        $myPicture->setFontProperties(array("FontName"=>LIBRARY . DS . 'pChart' . DS . 'fonts' . DS. 'SIMKAI.TTF', "FontSize"=>10, "R"=>0, "G"=>0, "B"=>0));
        
        $myPicture->setGraphArea(50, 10, $width - 20, $height - 30);
        
        $scaleSettings = array("XMargin" => 30, "YMargin" => 0, "DrawXLines" => FALSE, "Floating" => TRUE, "GridR" => 0, "GridG" => 0, "GridB" => 0, "DrawSubTicks" => TRUE, "CycleBackground" => TRUE);
        $myPicture->drawScale($scaleSettings);
        
        $myPicture->drawLineChart(array("BreakVoid" => FALSE, "VoidTicks" =>0, "DisplayValues" => FALSE, "ForceColor" => FALSE, 'ForceR' => 255, 'ForceG' => 0, 'ForceB' => 0,'DisplayR' => 96, 'DisplayG' => 0, 'DisplayB' => 0));
        $myPicture->drawPlotChart(array("DisplayValues" => TRUE, "PlotBorder" => TRUE, "BorderSize" => 2, "Surrounding" => 0, "BorderAlpha" => 100 ,"DisplayR" => 0, "DisplayG" => 0, "DisplayB" => 0));
        
        $myPicture->drawLineChart();
        $myPicture->autoOutput("pictures/WeekTemperatureChart.drawLineChart.png");
        
    }

    /**
     * 生成一月身高曲线图
     */
    function genMonthHeightChart($data, $width=700, $height=230) {
        
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pData.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pDraw.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pImage.class.php');

        foreach ($data as $k => $v) {
            if (empty($v)){
                $heightList[] = VOID;
            } else {
                $heightList[] = $v['height']/10;
            }
            $dateList[] = date("j", strtotime($k));
        }

        $myData = new pData();
        
        $myData->setAxisUnit(0, '厘米');
        
        $myData->AddPoints($heightList, "Probe 1");
        $myData->setSerieWeight("Probe 1", 1);
        
        $serieSetings = array("R"=>0, "G"=>0, "B"=>0, "Alpha"=>20);
        $myData->setPalette('Height', $serieSetings);
        
        $myData->addPoints($dateList, "Labels");
        $myData->setSerieDescription("Labels", "D");
        $myData->setAbscissa("Labels");
        
        $myPicture->Antialias = true;
        
        $myPicture = new pImage($width, $height, $myData);
        
        $Settings = array("R" => 255, "G" => 255, "B" => 255, "Dash" => 0, "DashR" => 255, "DashG" => 255, "DashB" => 255);
        $myPicture->drawFilledRectangle(0, 0, $width, $height, $Settings);
        
        $myPicture->setFontProperties(array("FontName"=>LIBRARY . DS . 'pChart' . DS . 'fonts' . DS. 'SIMKAI.TTF', "FontSize"=>10, "R"=>0, "G"=>0, "B"=>0));
        
        $myPicture->setGraphArea(50, 10, $width - 20, $height - 30);
        
        $scaleSettings = array("XMargin" => 0, "YMargin" => 0, "DrawXLines" => FALSE, "Floating" => TRUE, "GridR" => 0, "GridG" => 0, "GridB" => 0, "DrawSubTicks" => TRUE, "CycleBackground" => TRUE);
        $myPicture->drawScale($scaleSettings);
        
        $myPicture->drawLineChart(array("BreakVoid" => FALSE, "VoidTicks" =>0, "DisplayValues" => FALSE, "ForceColor" => FALSE, 'ForceR' => 255, 'ForceG' => 0, 'ForceB' => 0,'DisplayR' => 96, 'DisplayG' => 0, 'DisplayB' => 0));
        $myPicture->drawPlotChart(array("DisplayValues" => FALSE, "PlotBorder" => TRUE, "BorderSize" => 2, "Surrounding" => 0, "BorderAlpha" => 100 ,"DisplayR" => 0, "DisplayG" => 0, "DisplayB" => 0));
        
        $myPicture->drawLineChart();
        $myPicture->autoOutput("pictures/genMonthHeightChart.drawLineChart.png");
        
    }
    
    /**
     * 生成一月体重曲线图
     */
    function genMonthWeightChart($data, $width=700, $height=230) {

        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pData.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pDraw.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pImage.class.php');
        
        foreach ($data as $k => $v) {
            if (empty($v)){
                $weightList[] = VOID;
            } else {
                $weightList[] = $v['weight'] / 1000;
            }
            $dateList[] = date("j", strtotime($k));
        }

        $myData = new pData();
        
        $myData->setAxisUnit(0, '公斤');
        
        $myData->AddPoints($weightList, "Probe 1");
        $myData->setSerieWeight("Probe 1", 1);
        
        $serieSetings = array("R"=>0, "G"=>0, "B"=>0, "Alpha"=>20);
        $myData->setPalette('Weight', $serieSetings);
        
        $myData->addPoints($dateList, "Labels");
        $myData->setSerieDescription("Labels", "D");
        $myData->setAbscissa("Labels");
        
        $myPicture->Antialias = true;
        
        $myPicture = new pImage($width, $height, $myData);
        
        $Settings = array("R" => 255, "G" => 255, "B" => 255, "Dash" => 0, "DashR" => 255, "DashG" => 255, "DashB" => 255);
        $myPicture->drawFilledRectangle(0, 0, $width, $height, $Settings);
        
        $myPicture->setFontProperties(array("FontName"=>LIBRARY . DS . 'pChart' . DS . 'fonts' . DS. 'SIMKAI.TTF', "FontSize"=>10, "R"=>0, "G"=>0, "B"=>0));
        
        $myPicture->setGraphArea(50, 10, $width - 20, $height - 30);
        
        $scaleSettings = array("XMargin" => 0, "YMargin" => 0, "Floating" => TRUE, "DrawXLines" => FALSE, "GridR" => 0, "GridG" => 0, "GridB" => 0, "DrawSubTicks" => TRUE, "CycleBackground" => TRUE);
        $myPicture->drawScale($scaleSettings);
        
        $myPicture->drawLineChart(array("BreakVoid" => FALSE, "VoidTicks" =>0, "DisplayValues" => FALSE, "ForceColor" => FALSE, 'ForceR' => 255, 'ForceG' => 0, 'ForceB' => 0,'DisplayR' => 96, 'DisplayG' => 0, 'DisplayB' => 0));
        $myPicture->drawPlotChart(array("DisplayValues" => FALSE, "PlotBorder" => TRUE, "BorderSize" => 2, "Surrounding" => 0, "BorderAlpha" => 100 ,"DisplayR" => 0, "DisplayG" => 0, "DisplayB" => 0));
        
        $myPicture->drawLineChart();
        $myPicture->autoOutput("pictures/genMonthWeightChart.drawLineChart.png");
        
    }
    
    /**
     * 生成一年身高曲线图
     */
    function genYearHeightChart($data, $width=700, $height=230) {
    
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pData.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pDraw.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pImage.class.php');
        
        $myData = new pData();
        
        foreach ($data as $k => $v) {
            if (!empty($v)){
                $heightList = $v['height']/10;
            } else {
                $heightList = VOID;
            }
            $myData->addPoints($heightList, "Probe");
            
            if (date("Y-m", strtotime($k)) == date("Y-m", strtotime($k))) {
                $dateList = date("n", strtotime($k)) . '月';
            }  
            $myData->addPoints($dateList, "Labels");
        }
        
        $myData->setAxisUnit(0, "厘米");
        $myData->setAbscissa("Labels");
        
        $myPicture = new pImage($width, $height, $myData);
        
        $myPicture->Antialias = TRUE;
    
        $myPicture->setFontProperties(array("FontName"=>LIBRARY . DS . 'pChart' . DS . 'fonts' . DS. 'SIMKAI.TTF', "FontSize"=>10, "R"=>0, "G"=>0, "B"=>0));
        
        $myPicture->setGraphArea(50, 10, $width - 20, $height - 30);

        $scaleSettings = array("XMargin" => 0, "YMargin" => 0, "DrawXLines" => FALSE, "Floating" => TRUE, "GridR" => 0, "GridG" => 0, "GridB" => 0, "DrawSubTicks" => TRUE, "CycleBackground" => TRUE, "RemoveSkippedAxis"=>TRUE, "LabelingMethod"=>LABELING_DIFFERENT);
        $myPicture->drawScale($scaleSettings);
    
        $myPicture->drawLineChart(array("BreakVoid" => FALSE, "VoidTicks" =>0, "DisplayValues" => FALSE, "ForceColor" => FALSE));
        $myPicture->autoOutput("pictures/genYearHeightChart.drawDerivative.simple.png");
    
    }
    /**
     * 生成一年体重曲线图
     */
    function genYearWeightChart($data, $width=700, $height=230) {
    
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pData.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pDraw.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pImage.class.php');
        
        $myData = new pData();
        
        foreach ($data as $k => $v) {
            if (!empty($v)){
                $weightList = $v['weight'] / 1000;
            } else {
                $weightList = VOID;
            }
            $myData->addPoints($weightList, "Probe");
            
            if (date("Y-m", strtotime($k)) == date("Y-m", strtotime($k))) {
                $dateList = date("n", strtotime($k)) . '月';
            }  
            $myData->addPoints($dateList, "Labels");
        }
                
        $myData->setAxisUnit(0, "公斤");
        
        $myData->setAbscissa("Labels");
        
        $myPicture = new pImage($width, $height, $myData);
        
        $myPicture->Antialias = TRUE;
        
        $myPicture->setFontProperties(array("FontName"=>LIBRARY . DS . 'pChart' . DS . 'fonts' . DS. 'SIMKAI.TTF', "FontSize"=>10, "R"=>0, "G"=>0, "B"=>0));

        $myPicture->setGraphArea(50, 10, $width - 20, $height - 30);

        $scaleSettings = array("XMargin" => 0, "YMargin" => 0, "Floating"=>TRUE, "DrawXLines" => FALSE, "GridR" => 0, "GridG" => 0, "GridB" => 0, "CycleBackground" => TRUE, "RemoveSkippedAxis"=>TRUE, "LabelingMethod"=>LABELING_DIFFERENT);
        $myPicture->drawScale($scaleSettings);
        
        $myPicture->drawLineChart(array("BreakVoid" => FALSE, "VoidTicks" =>0, "DisplayValues" => FALSE, "ForceColor" => FALSE, 'ForceR' => 255, 'ForceG' => 0, 'ForceB' => 0,'DisplayR' => 96, 'DisplayG' => 0, 'DisplayB' => 0));
        $myPicture->autoOutput("pictures/genYearWeightChart.drawDerivative.simple.png");
    
    }
    /**
     * 生成总的身高曲线图
     */
    function genQuarterHeightChart($data, $width=700, $height=230){
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pData.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pDraw.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pImage.class.php');
        
        $myData = new pData();
        $i = 1;
        foreach ($data as $k => $v) {
            if (!empty($v)){
                $heightList = $v['height']/10;
            } else {
                $heightList = VOID;
            }
            $myData->addPoints($heightList, "Probe");
            
            if (date("Y-m", strtotime($k)) == date("Y-m", strtotime($k))) {
                $dateList = $i;
            }  
            $myData->addPoints($dateList, "Labels");
            $i++;
        }
        
        $myData->setAxisUnit(0, "厘米");
        $myData->setAbscissa("Labels");
        
        $myPicture = new pImage($width, $height, $myData);
        
        $myPicture->Antialias = TRUE;
    
        $myPicture->setFontProperties(array("FontName"=>LIBRARY . DS . 'pChart' . DS . 'fonts' . DS. 'SIMKAI.TTF', "FontSize"=>10, "R"=>0, "G"=>0, "B"=>0));
        
        $myPicture->setGraphArea(50, 10, $width - 20, $height - 30);

        $scaleSettings = array("XMargin" => 0, "YMargin" => 0, "DrawXLines" => FALSE, "Floating" => TRUE, "GridR" => 0, "GridG" => 0, "GridB" => 0, "DrawSubTicks" => TRUE, "CycleBackground" => TRUE, "RemoveSkippedAxis"=>TRUE, "LabelingMethod"=>LABELING_DIFFERENT);
        $myPicture->drawScale($scaleSettings);
    
        $myPicture->drawLineChart(array("BreakVoid" => FALSE, "VoidTicks" =>0, "DisplayValues" => FALSE, "ForceColor" => FALSE));
        $myPicture->autoOutput("pictures/genYearHeightChart.drawDerivative.simple.png");
        
    }
    
    /**
     * 生成总的体重曲线图
     */
    function genQuarterWeightChart($data, $width=700, $height=230){
        
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pData.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pDraw.class.php');
        include(LIBRARY . DS . 'pChart' . DS . 'class' . DS . 'pImage.class.php');
        
        $myData = new pData();
        $i = 1;
        foreach ($data as $k => $v) {
            if (!empty($v)){
                $weightList = $v['weight'] / 1000;
            } else {
                $weightList = VOID;
            }
            $myData->addPoints($weightList, "Probe");
            
            if (date("Y-m-d", strtotime($k)) == date("Y-m-d", strtotime($k))) {
                $dateList = $i;
            }  
            $myData->addPoints($dateList, "Labels");
            $i++;
        }
        
        $myData->setAxisUnit(0, "公斤");
        
        $myData->setAbscissa("Labels");
        
        $myPicture = new pImage($width, $height, $myData);
        
        $myPicture->Antialias = TRUE;
        
        $myPicture->setFontProperties(array("FontName"=>LIBRARY . DS . 'pChart' . DS . 'fonts' . DS. 'SIMKAI.TTF', "FontSize"=>10, "R"=>0, "G"=>0, "B"=>0));

        $myPicture->setGraphArea(50, 10, $width - 20, $height - 30);

        $scaleSettings = array("XMargin" => 0, "YMargin" => 0, "Floating"=>TRUE, "DrawXLines" => FALSE, "GridR" => 0, "GridG" => 0, "GridB" => 0, "CycleBackground" => TRUE, "RemoveSkippedAxis"=>TRUE, "LabelingMethod"=>LABELING_DIFFERENT);
        $myPicture->drawScale($scaleSettings);
        
        $myPicture->drawLineChart(array("BreakVoid" => FALSE, "VoidTicks" =>0, "DisplayValues" => FALSE, "ForceColor" => FALSE, 'ForceR' => 255, 'ForceG' => 0, 'ForceB' => 0,'DisplayR' => 96, 'DisplayG' => 0, 'DisplayB' => 0));
        $myPicture->autoOutput("pictures/genYearWeightChart.drawDerivative.simple.png");
        
    }
    
}

?>