<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>ボーダレスワーキング</title>
</head>
<body>

	<div class="invoice_wrapper">
		<div class="invoice_tit">
			<!-- <h2>請求書</h2> -->
			<h2><span>請</span><span>求</span><span>書</span></h2>
		</div>
		
		<div class="row" style="margin-bottom:0px">
			<div class="col-md-6 f-left" style="margin-top:5px;margin-bottom:0px">
				
				<table class="sama-table">
					<tr>
						<td style="width:80%">{{ $data->recruiter_name }}</td>
						<td style="width:20%;text-align:right;">御中</td>
					</tr>
				</table>
				<p style="margin-top:20px;">下記のとおり御請求いたします。</p>
				
				<div class="transfer-info" style="margin-bottom:0px">
					<p>(振込先)</p>
					<p>&nbsp;&nbsp;&nbsp; 三菱UFJ銀行　西池袋支店</p>
					<p>&nbsp;&nbsp;（普）№0010032</p>
					<p>&nbsp;&nbsp;&nbsp; カ)　トラストグロース</p>
					<p>(支払い期限)</p>
					<p>&nbsp;&nbsp;&nbsp; 請求書発行日の翌月末日まで</p>
				</div>
				
			</div>
			<div class="col-md-6 f-right" style="margin-bottom:0px">
				<table class="date-table">
					<tr>
						<td width='130px'>請求書番号</td>
						<td>{{ $data->payment->invoice_number }}</td>
					</tr>
					<tr>
						<td>請求書発行日</td>
						<td>{{ $data->payment->invoicedate }}</td>
					</tr>
				</table>
				<div style="clear:both;"></div>
				
                <p style="margin-top: 30px;margin-bottom:0px">株式会社Trust Growth</p>
				<div class="company-info mb-2 with-sign" style="margin-bottom:0px">		
                <br><br>			
                    <label>〒163-0713</label><br><br>
					<label>東京都新宿区西新宿2-7-1</label><br><br>
					<label>小田急第一生命ビル13F</label><br><br>
					<label>代表取締役社長　長井　博</label><br><br>		
				<p style="margin-top: 30px;">
                    <label>TEL : 03-6911-3010</label><br><br>
					<label>FAX : 03-6911-3011</label>
                </p>
					
					
				</div>
			</div>
		</div>
		<div class="row  mb-2">
			<div class="col-md-6 f-left">
				<h3 class="border-underline">御請求金額 &nbsp;&nbsp;&nbsp;<span>¥{{ number_format($data->payment->invoice_amount) }}ー（税込）</span></h3>
			</div>			
		</div>
		<table class="tbl-02 mb-4 invoice-table">
			<thead>
				<tr>
					<th width="60px">No.</th>
					<th>摘要</th>
					<th width="60px">数量</th>
					<th width="100px">単価</th>
					<th width="100px">金額</th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td align="center">1</td>
					<td>ボーダレスワーキング仲介手数料【{{ $data->payment->management_number }}】</td>
					<td class="text-right">1</td>
					<td class="text-right" width="100px">{{ number_format($data->payment->default_amount) }}</td>
					<td class="text-right" width="100px">{{ number_format($data->payment->default_amount) }}</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" align="center">小計</td>
					<td class="text-right" width="100px">{{ number_format($data->payment->default_amount) }}</td>
					<!-- <td width="100px"></td> -->
				</tr>
				<tr>
					<td colspan="4" align="center">消費税</td>
					<!-- <td width="100px"></td> -->
					<td class="text-right" width="100px">{{ number_format($data->payment->tax) }}</td>
				</tr>
				<tr>
					<td colspan="4" align="center">合計</td>
					<!-- <td width="100px"></td> -->
					<td class="text-right" width="100px">{{ number_format($data->payment->invoice_amount) }}</td>
				</tr>
			</tfoot>
		</table>
		<div class="remark">
            
			<table>
				<tr>
					<td class="remark-first">備考</td>
					<td class="remark-box" style="height: 130px;">{!! nl2br($data->payment->remark) !!}</td>
					<!-- <td class="remark-box" style="height: 130px;">{{ $data->payment->remark}}</td> -->
				</tr>
			</table>
		</div>
	</div>

	<style>
        @page {
            margin: 0px 30px;
		}
		.sama-table {
			margin-top:18px;
			width: 80%;
			border-bottom: 1px solid black;
		}
		.sama-table tr {
			/* border-bottom: 1px solid black; */
		}
		.sama-table tr td {
			padding: 0px;
			border: none;
		}
		.date-table{
			float: right;
		}
		.date-table tr td {
			border: none;
			padding: 0px;
		}
		.invoice_wrapper {
			padding: 40px 40px 30px 40px;
		}
        body {
			font-family: 'mspgothic', sans-serif;
            font-size: 16px;
            margin: 0px;
            font-weight: normal;
		}
		.invoice_tit {
			width: 30%;
            padding-top: 25px;
			margin: 0 auto;
			text-align: center;
			margin-bottom: 40px;
			/* margin-top: 40px; */
		}
		h2 {
			margin: 0;
            /* padding-top: 30px; */
			font-size: 22px;
			letter-spacing: 30px;
    		border-bottom: 3px double;
			font-family: 'mspgothic', sans-serif;
            font-weight: normal;
            text-align: center;
            /* background:#ccc; */
            padding-left: 30px;
            padding-right: 0px;
            
		}
		/* .invoice_tit  h2 span:last-child {
			margin-right: 30px;
		} */
        div .inline {
            color: black;
            float: left;
            line-height: 1;
            font-size: 16px;
        }
        /* .row {
			width: 100%;
			display: flex;
			justify-content: space-between;
		} */
		*, :after, :before {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}
		.row {
			overflow: hidden;
		}
		.row:after,
		.row:before {
			display: table;
			content: " "
		}
        /* div .row:after {
            clear: both;
            width: 100%;
		} */
		.col-md-6 {
			padding-bottom: 10px;
            width: 48%;
		}
		.f-left {
			float: left;
		}
		.f-right {
			float: right;
		}
		
		.ml-auto {
			margin-left: auto;
		}
		.mb-2 {
			margin-bottom: 0.5rem !important;
		}
		.mb-3{
			margin-bottom: 1rem !important;
		}
		.mb-4{
			margin-bottom: 1.5rem !important;
		}
        .report-title {
            float: left;
            font-size: 16px;
            font-weight: normal;
            text-align: center;
            width: 100%;
        }
        .right {
            float: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            margin: 0;
            padding: 5px;
            text-align: left;
            font-size: 16px;
            border: #444 1px solid;
        }
        .invoice-table td{
            font-size: 16px!important;
        }
        tr {
            width: 100%;
        }
        th {
            padding: 5px;
            border : 1px solid #444;
            font-weight: normal;
			color: black;
			/* background: #cae271ab; */
			text-align: center;
        }
        td a {
            text-decoration: none;
            color: black;
        }
        .summary-table {
            width: 30%;
            margin-bottom: 20px;
            color: black
        }
        .summary-table tr {
            CreditAdministrationReplacementsReport border-bottom: #eee 0.5pt solid;
            border-top: #eee 0.5pt solid
        }
        .key {
            padding: 10px;
            font-weight: normal;
            background: #eee
        }
        .value {
            padding: 10px;
            text-align: center;
        }
        .col-md-2 {
            width: 20%;
        }
		.text-right {
			text-align: right!important;
		}
		.text-center {
			text-align: center!important;
		}
		.text-underline {
			position: relative;
			border-bottom: 1px solid;
		}
		.with-sign {
			background: url('{{ $sign }}') no-repeat right;
			background-size: 27%;
            /* background-position: bottom;  */
            /* background-position-x: right; */
            background-position: 170px 30px;
		}
		.border-underline {
			margin-bottom: 0;
			border-bottom: 1px solid #000;
            font-weight: normal!important;
		}
        th{
            font-weight: normal!important;
        }
		.branch-info {
			margin-left: 30px;
		}
		.branch-info p {
			line-height: 0.5rem;
		}
        .remark {
            margin-top: 40px;
        }
		.remark-box {
			background: #FFF;
			white-space: nowrap !important;
		}
		.company-info {
			line-height: 0.5rem;
            font-size: 16px;			
		}
		.remark td.remark-first {
			width: 60px;
			padding: 30px 0;
			text-align: center;
			/* background: #cae271ab; */
		}
		.transfer-info {
			margin-top: 20px;
		}
		.transfer-info  p {
			margin: 0;
		}
		.invoice_no {
			margin-top: 20px;
			text-align: right;
		}
		.tbl-01 td{
			padding: 20px 5px;
		}
		.tbl-02 td:first-child , .tbl-02 td:nth-child(3) {
			/* width: 8%; */
			text-align: center;
		}
		.tbl-02 td:nth-child(2) {
			/* width: 42%; */
		}
		/* .tbl-02 td:last-child , .tbl-02 td:nth-child(4) {
			width: 20%;
			text-align: right;
		} */
		.tbl-02 td:nth-child(4)  {
			width: 20%;
		}
		.invoice-table td, .invoice-table th {
			line-height: 20px;
		}
		
    </style>
</body>
</html>