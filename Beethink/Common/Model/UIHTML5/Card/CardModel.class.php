<?php
class CardModel
{
	/**
	 * 今日浏览情况
	 */
	public function  visit($num,$num2,$num3)
	{
		$html='<div class="col-sm-6 col-md-3">
          <div class="panel panel-success panel-stat">
            <div class="panel-heading">

              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
                    <img src="images/is-user.png" alt="">
                  </div>
                  <div class="col-xs-8">
                    <small class="stat-label">Visits Today</small>
                    <h1>'.$num.'</h1>
                  </div>
                </div><!-- row -->

                <div class="mb15"></div>

                <div class="row">
                  <div class="col-xs-6">
                    <small class="stat-label">Pages / Visit</small>
                    <h4>'.$num2.'</h4>
                  </div>

                  <div class="col-xs-6">
                    <small class="stat-label">% New Visits</small>
                    <h4>'.$num3.'</h4>
                  </div>
                </div><!-- row -->
              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div>';

        return $html;
	}
	/**
	 * 今日页面访问量和增长百分比
	 */
	public function pageView($num,$num2)
	{
		$html='<div class="col-sm-6 col-md-3">
          <div class="panel panel-primary panel-stat">
            <div class="panel-heading">

              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
                    <img src="images/is-document.png" alt="">
                  </div>
                  <div class="col-xs-8">
                    <small class="stat-label">Page Views</small>
                    <h1>'.$num.'</h1>
                  </div>
                </div><!-- row -->

                <div class="mb15"></div>

                <small class="stat-label">% Bounce Rate</small>
                <h4>'.$num2.'</h4>

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div>';


        return $html;
	}
	/**
	 * 今日收入
	 */
	public function Earnings($num1,$num2)
	{
		$html='<div class="col-sm-6 col-md-3">
          <div class="panel panel-dark panel-stat">
            <div class="panel-heading">

              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
                    <img src="images/is-money.png" alt="">
                  </div>
                  <div class="col-xs-8">
                    <small class="stat-label">今日营业额</small>
                    <h1>'.$num1.'</h1>
                  </div>
                </div><!-- row -->

                <div class="mb15"></div>

                <div class="row">
                  <div class="col-xs-6">
                    <small class="stat-label">今日订单数量</small>
                    <h4>'.$num2.'</h4>
                  </div>
                </div><!-- row -->

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div>';	
	}
	/**
	 * IP 磁铁
	 $IP = 本次登陆ip地址
	 $lastLoginTime= 上次登陆时间戳
	 */
	public function ip($ip,$lastLoginTime,$loginTimes=0)
	{
		$html='<div class="col-sm-6 col-md-3">
          <div class="panel panel-success panel-stat">
            <div class="panel-heading">

              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
                    <img src="images/is-user.png" alt="">
                  </div>
                  <div class="col-xs-8">
                    <small class="stat-label">上次登陆IP</small>
                    <h1>'.$ip.'</h1>
                  </div>
                </div><!-- row -->

                <div class="mb15"></div>

                <div class="row">
                  <div class="col-xs-6">
                    <small class="stat-label">上次登陆时间</small>
                    <h4>'.$lastLoginTime.'</h4>
                  </div>

                  <div class="col-xs-6">
                    <small class="stat-label">登陆次数</small>
                    <h4>'.$loginTimes.'</h4>
                  </div>
                </div><!-- row -->
              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div>';

        return $html;
	}
	/**
	 * 日期
	 */
	public function curDate($curDate)
	{
		$html='<div class="col-sm-10 col-md-2">
              <div class="panel panel-warning panel-alt widget-today">
                <div class="panel-heading text-center">
                  <i class="fa fa-calendar-o"></i>
                </div>
                <div class="panel-body text-center">
                  <h3 class="today">'.$curDate.'</h3>
                </div><!-- panel-body -->
              </div><!-- panel -->
            </div>';

        return $html;
	}
	/**
	 * 时间
	 */
	public function curTime($curTime)
	{
		$html='<div class="col-xs-6">
              <div class="panel panel-danger panel-alt widget-time">
                <div class="panel-heading text-center">
                  <i class="glyphicon glyphicon-time"></i>
                </div>
                <div class="panel-body text-center">
                  <h3 class="today">'.$curTime.'</h3>
                </div><!-- panel-body -->
              </div><!-- panel -->
            </div>';

		return $html;
	}
}

?>