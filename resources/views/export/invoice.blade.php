<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>invoice order receipt - Bootdey.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('css/boostrap2.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
  <div class="row">
    <!-- BEGIN INVOICE -->
    <div class="col-xs-12">
      <div class="grid invoice">
        <div class="grid-body">
          <div class="invoice-title">
            <div class="row">
              <div class="col-xs-12">
                <img src="{{ $logo  }}" alt="" height="35">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-xs-12">
                <h2>{{ $doc['title']  }}<br>
                  <span class="small">{{ $data->transaction_no }}</span>
                </h2>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xs-6">
              <address>
                <strong>Billed To:</strong><br>
                {{ $doc['company_name'] }}<br>
                {{ $doc['company_address'] }}<br>
                <abbr title="Phone">P:</abbr> {{ $doc['company_phone'] }}
              </address>
            </div>
            <div class="col-xs-6 text-right">
              <address>
                <strong>Shipped To:</strong><br>
                {{ $doc['customer_name'] }}<br>
                {{ $doc['customer_address'] }}<br>
                <abbr title="Phone">P:</abbr> {{ $doc['customer_phone'] }}
              </address>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-6">
              <address>
                <strong>Payment Method:</strong><br>
                Visa ending **** 1234<br>
                h.elaine@gmail.com<br>
              </address>
            </div>
            <div class="col-xs-6 text-right">
              <address>
                <strong>Order Date:</strong><br>
                17/06/14
              </address>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <table class="table table-striped">
                <thead>
                <tr class="line">
                  <td><strong>#</strong></td>
                  <td class="text-center"><strong>PROJECT</strong></td>
                  <td class="text-center"><strong>HRS</strong></td>
                  <td class="text-right"><strong>RATE</strong></td>
                  <td class="text-right"><strong>SUBTOTAL</strong></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>1</td>
                  <td><strong>Template Design</strong><br>A website template is a pre-designed webpage, or set of
                    webpages, that anyone can modify with their own content and images to setup a website.
                  </td>
                  <td class="text-center">15</td>
                  <td class="text-center">$75</td>
                  <td class="text-right">$1,125.00</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td><strong>Template Development</strong><br>Web development is a broad term for the work involved in
                    developing a web site for the Internet (World Wide Web) or an intranet (a private network).
                  </td>
                  <td class="text-center">15</td>
                  <td class="text-center">$75</td>
                  <td class="text-right">$1,125.00</td>
                </tr>
                <tr class="line">
                  <td>3</td>
                  <td><strong>Testing</strong><br>Take measures to check the quality, performance, or reliability of
                    (something), especially before putting it into widespread use or practice.
                  </td>
                  <td class="text-center">2</td>
                  <td class="text-center">$75</td>
                  <td class="text-right">$150.00</td>
                </tr>
                <tr>
                  <td colspan="3"></td>
                  <td class="text-right"><strong>Taxes</strong></td>
                  <td class="text-right"><strong>N/A</strong></td>
                </tr>
                <tr>
                  <td colspan="3">
                  </td>
                  <td class="text-right"><strong>Total</strong></td>
                  <td class="text-right"><strong>$2,400.00</strong></td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-right identity">
              <p>Designer identity<br><strong>Jeffrey Williams</strong></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END INVOICE -->
  </div>
</div>

<style type="text/css">
  body {
    margin-top: 20px;
    background: #eee;
  }

  .invoice {
    padding: 30px;
  }

  .invoice h2 {
    margin-top: 0px;
    line-height: 0.8em;
  }

  .invoice .small {
    font-weight: 300;
  }

  .invoice hr {
    margin-top: 10px;
    border-color: #ddd;
  }

  .invoice .table tr.line {
    border-bottom: 1px solid #ccc;
  }

  .invoice .table td {
    border: none;
  }

  .invoice .identity {
    margin-top: 10px;
    font-size: 1.1em;
    font-weight: 300;
  }

  .invoice .identity strong {
    font-weight: 600;
  }


  .grid {
    position: relative;
    width: 100%;
    background: #fff;
    color: #666666;
    border-radius: 2px;
    margin-bottom: 25px;
    box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
  }

</style>

<script type="text/javascript">

</script>
</body>
</html>
