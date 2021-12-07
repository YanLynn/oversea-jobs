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
			<h2><span>請</span><span>求</span><span>書</span></h2>
		</div>
		<div class="row">
			<div class="col-md-6 f-left">
				<div class="p-name">
					{{-- <p><span class="text-underline">{{ $data->recruiter_name }}</span> 御中</p>
					<p>ご担当: {{ $data->incharge_name }} 様</p> --}}
					<!-- <p class="text-underline"><span>{{ $data->recruiter_name }}</span><span>  様</span></p> -->
					<table class="sama-table">
						<tr>
							<td style="width:90%">{{ $data->recruiter_name }}</td>
							<td style="width:10%;text-align:right;">様</td>
						</tr>
					</table>
					<p>下記のとおりご請求いたします。</p>
				</div>
				{{-- <div style="padding-right: 1.5rem;">
					<p class="border-underline"><span class="">件名: {{ $data->title }}</span></p>
					<p>下記の通り，ご請求申し上げます。</p>
				</div> --}}
				<div class="transfer-info">
					<p>(振込先)</p>
					<p class="ml-3">三菱UFJ銀行􀀀西池袋支店</p>
					<p class="ml-3">（普）№0010032</p>
					<p class="ml-3">カ)􀀀トラストグロース</p>
					<p>(支払い期限)<br>
						<span class="ml-3">請求書発行日の翌月末日まで</span>
					</p>
				</div>
				{{-- <div class="transfer-info">
					<p>(支払い期限)<br>
						<span class="ml-2">請求書発行日の翌月末日まで</span>
					</p>
				</div> --}}
			</div>
			<div class="col-md-6 with-sign f-right" style="padding-right:0px;">
				{{-- <div>
					<p>請求書 No. {{ $data->payment->invoice_number }}</p>
					<p>請求日 {{ $data->payment->invoicedate }}</p>
				</div> --}}
				<div class="invoice-txt">
					<div class="row">
						<div class="col-left">請求番号</div>
						<div class="col-right">{{ $data->payment->invoice_number }}</div>
					</div>
					<div class="row">
						<div class="col-left">請求書発行日</div>
						<div class="col-right">{{ $data->payment->invoicedate }}</div>
					</div>
				</div>
				<div class="company-info">
					<p>株式会社Trust Growth</p>
					<p>〒163-0713</p>
					<p>東京都新宿区西新宿2-7-1</p>
					<p>小田急第一生命ビル13F</p>
					<p>代表取締役社長􀀀長井􀀀博</p>				
				</div>
				<div class="company-phone">
					<p>TEL : 03-6911-3010</p>
					<p>FAX : 03-6911-3011</p>
					{{-- <p>E-mail: who@management-partners.co.jp</p>
					<p>担当: Who</p> --}}
				</div>
			</div>
		</div>
		<div class="row  mb-2">
			<div class="col-md-6 f-left">
				<h3 class="border-underline">御請求金額   <span class="right">¥{{ number_format($data->payment->invoice_amount) }}（鋭込）</span></h3>
			</div>
			{{-- <div class="col-md-6 f-right">
				<table class="tbl-01">
					<thead>
						<tr>
							<th>部長</th>
							<th>課長</th>
							<th>担当</th>			
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>		
				</table>
			</div> --}}
		</div>
		<table class="tbl-02 mb-4">
			<thead>
				<tr>
					<th>No.</th>
					<th>摘要</th>
					<th>数量</th>
					<th>単価</th>
					<th>金額</th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td align="center">1</td>
					<td>{{ $data->title }}</td>
					<td class="text-right">1</td>
					<td class="text-right">{{ number_format($data->payment->default_amount) }}円</td>
					<td class="text-right">{{ number_format($data->payment->default_amount) }}円</td>
				</tr><tr>
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
					<td colspan="3" align="center">小計</td>
					<td class="text-right">{{ number_format($data->payment->default_amount) }}円</td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3" align="center">消費税</td>
					<td class="text-right">{{ number_format($data->payment->tax) }}円</td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3" align="center">合計</td>
					<td class="text-right">{{ number_format($data->payment->invoice_amount) }}円</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
		<div class="remark">
			<table>
				<tr>
					<td class="remark-first">備考</td>
					<td class="remark-box">{!! nl2br($data->payment->remark) !!}</td>
				</tr>
			</table>
		</div>
	</div>

	<style>
        @page {
            margin: 0px 30px;
		}
		.invoice_wrapper {
			padding: 20px;
		}
        body {
			font-family: 'notosanscjktc', sans-serif;
            font-size: 16px;
            margin: 0px;
		}
		.invoice_tit {
			width: 51%;
			margin: 0 auto 30px;
			text-align: center;
		}
		h2 {
			margin: 0;
			font-size: 30px;
			letter-spacing: 30px;
    		border-bottom: 3px double;
			font-family: 'notosanscjktc', sans-serif;
			font-weight: 500;
		}
		.invoice_tit  h2 span:last-child {
			margin-right: -25px;
		}
        div .inline {
            color: black;
            float: left;
            line-height: 1;
            font-size: 13px;
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
		.col-left {
			float: left;
			width: 90px;
		}
		.col-right {
			float: left;
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
		  
		.ml-3{
			margin-left: 1rem !important;
		}
		.p-name {
			margin-top: 20px;
		}
		.invoice-txt {
			margin-bottom: 30px
		}
		.invoice-no {
			margin-right: 30px;
		}
		.invoice-date {
			margin-right: 10px;
		}
        .report-title {
            float: left;
            font-size: 1.3em;
            font-weight: 100;
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
            font-size: 12px;
            border: #444 1px solid;
        }
        tr {
            width: 100%;
        }
        th {
            padding: 5px;
            border : 1px solid #444;
            font-weight: bold;
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
            font-weight: bold;
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
			margin-bottom: 18px;
			border-bottom: 1px solid;
		}
		.with-sign {
			background: url('{{ $sign }}') no-repeat right;
			background-size: 40%;
			/* background-position: 100px; */
		}
		.border-underline {
			margin-bottom: 0;
			border-bottom: 1px solid #000;
		}
		.branch-info {
			margin-left: 30px;
		}
		.branch-info p {
			line-height: 0.5rem;
		}
		.remark-box {
			background: #FFF;
			white-space: nowrap !important;
		}
		.company-info {
			margin-bottom: 35px;
		}
		.company-info p:first-child {
			margin-bottom: 20px;
		}
		.company-info p {
			line-height: 0.35rem;
		}
		.company-phone p  {
			line-height: 0.43rem;
		}
		.remark td.remark-first {
			width: 60px;
			padding: 50px 0;
			text-align: center;
			/* background: #cae271ab; */
		}
		.transfer-info {
			margin-top: 30px;
		}
		.transfer-info  p {
			margin: 0;
		}
		.tbl-01 td{
			padding: 20px 5px;
		}
		.tbl-02 td:nth-child(3)  {
			width: 9%;
		}
		.tbl-02 td:first-child , .tbl-02 td:nth-child(3) {
			text-align: center;
		}
		.tbl-02 td:nth-child(4) {
			width: 18%;
		}
		/* .tbl-02 td:last-child , .tbl-02 td:nth-child(4) {
			width: 20%;
			text-align: right;
		} */
		.tbl-02 td:nth-child(4)  {
			width: 20%;
		}
		.tbl-02 td{
			/* padding: 15px 0; */
            line-height: 20px;
		}
		.sama-table {
			margin-top:18px;
			width: 75%;
			border-bottom: 1px solid black;
		}
		.sama-table tr {
			/* border-bottom: 1px solid black; */
		}
		.sama-table tr td {
			padding: 0px;
			border: none;
		}
    </style>
</body>
</html>