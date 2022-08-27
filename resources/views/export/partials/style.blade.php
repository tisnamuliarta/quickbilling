<style>
  @font-face {
    font-family: 'founder';
    src: url({{ storage_path('fonts/founder.ttf') }}) format("truetype");
    font-weight: 500;
  }

  * {
    font-family: 'founder';
  }

  .simsun {
    font-family: 'founder';
  }

  body {
    color: #000000;
    font-size: .75rem;
    font-weight: 400;
    line-height: 1.3;
  }

  .text-lowercase {
    text-transform: lowercase !important;
  }

  .text-uppercase {
    text-transform: uppercase !important;
  }

  .text-capitalize {
    text-transform: capitalize !important;
  }

  .text-left {
    text-align: left !important;
  }

  .text-right {
    text-align: right !important;
  }

  .text-center {
    text-align: center !important;
  }

  table {
    border-collapse: collapse;
    border-spacing: 0;
  }

  .table {
    width: 100%;
    /*margin-bottom: 1rem;*/
    color: #212529;
  }

  .table th,
  .table td {
    padding: 0.2rem .4rem;
    vertical-align: top;
    border-top: 1px solid #000000;
    page-break-inside: initial;
  }

  .table thead th {
    vertical-align: center;
    border-bottom: 1px solid #000000;
  }

  .table tbody + tbody {
    border-top: 1px solid #000000;
  }

  .table-sm th,
  .table-sm td {
    padding: 0.2rem .4rem;
  }

  .table-bordered {
    border: 1px solid #000000;
  }

  .table-bordered th,
  .table-bordered td {
    border: 1px solid #000000;
  }

  .table-bordered thead th,
  .table-bordered thead td {
    border-bottom-width: 1px;
  }

  .table-borderless {
    width: 100%;
  }

  .table-borderless th,
  .table-borderless td,
  .table-borderless thead th,
  .table-borderless tbody + tbody {
    border: 0 !important;
  }

  .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
  }

  .table-hover tbody tr:hover {
    color: #212529;
    background-color: rgba(0, 0, 0, 0.075);
  }

  .table-primary,
  .table-primary > th,
  .table-primary > td {
    background-color: #b8daff;
  }

  .table-primary th,
  .table-primary td,
  .table-primary thead th,
  .table-primary tbody + tbody {
    border-color: #7abaff;
  }

  .table-hover .table-primary:hover {
    background-color: #9fcdff;
  }

  .table-hover .table-primary:hover > td,
  .table-hover .table-primary:hover > th {
    background-color: #9fcdff;
  }

  .table-secondary,
  .table-secondary > th,
  .table-secondary > td {
    background-color: #d6d8db;
  }

  .table-secondary th,
  .table-secondary td,
  .table-secondary thead th,
  .table-secondary tbody + tbody {
    border-color: #b3b7bb;
  }

  .table-hover .table-secondary:hover {
    background-color: #c8cbcf;
  }

  .table-hover .table-secondary:hover > td,
  .table-hover .table-secondary:hover > th {
    background-color: #c8cbcf;
  }

  .table-success,
  .table-success > th,
  .table-success > td {
    background-color: #c3e6cb;
  }

  .table-success th,
  .table-success td,
  .table-success thead th,
  .table-success tbody + tbody {
    border-color: #8fd19e;
  }

  .table-hover .table-success:hover {
    background-color: #b1dfbb;
  }

  .table-hover .table-success:hover > td,
  .table-hover .table-success:hover > th {
    background-color: #b1dfbb;
  }

  .table-info,
  .table-info > th,
  .table-info > td {
    background-color: #bee5eb;
  }

  .table-info th,
  .table-info td,
  .table-info thead th,
  .table-info tbody + tbody {
    border-color: #86cfda;
  }

  .table-hover .table-info:hover {
    background-color: #abdde5;
  }

  .table-hover .table-info:hover > td,
  .table-hover .table-info:hover > th {
    background-color: #abdde5;
  }

  .table-warning,
  .table-warning > th,
  .table-warning > td {
    background-color: #ffeeba;
  }

  .table-warning th,
  .table-warning td,
  .table-warning thead th,
  .table-warning tbody + tbody {
    border-color: #ffdf7e;
  }

  .table-hover .table-warning:hover {
    background-color: #ffe8a1;
  }

  .table-hover .table-warning:hover > td,
  .table-hover .table-warning:hover > th {
    background-color: #ffe8a1;
  }

  .table-danger,
  .table-danger > th,
  .table-danger > td {
    background-color: #f5c6cb;
  }

  .table-danger th,
  .table-danger td,
  .table-danger thead th,
  .table-danger tbody + tbody {
    border-color: #ed969e;
  }

  .table-hover .table-danger:hover {
    background-color: #f1b0b7;
  }

  .table-hover .table-danger:hover > td,
  .table-hover .table-danger:hover > th {
    background-color: #f1b0b7;
  }

  .table-light,
  .table-light > th,
  .table-light > td {
    background-color: #fdfdfe;
  }

  .table-light th,
  .table-light td,
  .table-light thead th,
  .table-light tbody + tbody {
    border-color: #fbfcfc;
  }

  .table-hover .table-light:hover {
    background-color: #ececf6;
  }

  .table-hover .table-light:hover > td,
  .table-hover .table-light:hover > th {
    background-color: #ececf6;
  }

  .table-dark,
  .table-dark > th,
  .table-dark > td {
    background-color: #c6c8ca;
  }

  .table-dark th,
  .table-dark td,
  .table-dark thead th,
  .table-dark tbody + tbody {
    border-color: #95999c;
  }

  .table-hover .table-dark:hover {
    background-color: #b9bbbe;
  }

  .table-hover .table-dark:hover > td,
  .table-hover .table-dark:hover > th {
    background-color: #b9bbbe;
  }

  .table-active,
  .table-active > th,
  .table-active > td {
    background-color: rgba(0, 0, 0, 0.075);
  }

  .table-hover .table-active:hover {
    background-color: rgba(0, 0, 0, 0.075);
  }

  .table-hover .table-active:hover > td,
  .table-hover .table-active:hover > th {
    background-color: rgba(0, 0, 0, 0.075);
  }

  .table .thead-dark th {
    color: #fff;
    background-color: #343a40;
    border-color: #454d55;
  }

  .table .thead-light th {
    color: #495057;
    background-color: #e9ecef;
    border-color: #000000;
  }

  .table-dark {
    color: #fff;
    background-color: #343a40;
  }

  .table-dark th,
  .table-dark td,
  .table-dark thead th {
    border-color: #454d55;
  }

  .table-dark.table-bordered {
    border: 0;
  }

  .table-dark.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(255, 255, 255, 0.05);
  }

  .table-dark.table-hover tbody tr:hover {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.075);
  }

  .clearfix::after {
    display: block;
    clear: both;
    content: "";
  }

  p {
    font-size: 14px;
  }


  @page {
    margin: 50px 20px 30px 20px;
  }

  body {
    margin: 50px 20px 30px 20px;
  }


  .page-number:before {
    /*counter-increment: page;*/
    /*counter-reset: page 0;*/
    /*content: "Page " counter(page);*/
  }

  /*header {*/
  /*  position: fixed;*/
  /*  left: 0px;*/
  /*  right: 0px;*/
  /*  height: 70px;*/
  /*  margin-bottom: 10px;*/
  /*  border-bottom: 3px solid #000000;*/
  /*}*/
  /*.header {*/
  /*  margin-bottom: 10px;*/
  /*  border-bottom: 3px solid #000000;*/
  /*}*/

  /*footer {*/
  /*  position: fixed;*/
  /*  bottom: 5px;*/
  /*  left: 0px;*/
  /*  right: 0px;*/
  /*  height: 50px;*/
  /*}*/


  .page-break {
    page-break-after: always;
  }

  .page-break1 {
    page-break-after: always;
  }

  .page-break:last-child {
    page-break-after: avoid;
  }

  .header {
    position: absolute;
    top: -80px;
    left: 20px;
    right: 20px;
    height: 30px;
    /*margin-bottom: 10px;*/
    /*border-bottom: 2px solid #2e2e2e;*/
  }

  .footer {
    position: absolute;
    bottom: 5px;
    left: 20px;
    right: 20px;
    height: 50px;
  }

  .disable-wrap {
    white-space: nowrap !important;
  }

  .container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
  }
  @media (min-width: 768px) {
    .container {
      width: 750px;
    }
  }
  @media (min-width: 992px) {
    .container {
      width: 970px;
    }
  }
  @media (min-width: 1200px) {
    .container {
      width: 1170px;
    }
  }
  .container-fluid {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
  }
  .row {
    margin-right: -15px;
    margin-left: -15px;
  }
  .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
  }
  .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
    float: left;
  }
  .col-xs-12 {
    width: 100%;
  }
  .col-xs-11 {
    width: 91.66666667%;
  }
  .col-xs-10 {
    width: 83.33333333%;
  }
  .col-xs-9 {
    width: 75%;
  }
  .col-xs-8 {
    width: 66.66666667%;
  }
  .col-xs-7 {
    width: 58.33333333%;
  }
  .col-xs-6 {
    width: 50%;
  }
  .col-xs-5 {
    width: 41.66666667%;
  }
  .col-xs-4 {
    width: 33.33333333%;
  }
  .col-xs-3 {
    width: 25%;
  }
  .col-xs-2 {
    width: 16.66666667%;
  }
  .col-xs-1 {
    width: 8.33333333%;
  }
  .col-xs-pull-12 {
    right: 100%;
  }
  .col-xs-pull-11 {
    right: 91.66666667%;
  }
  .col-xs-pull-10 {
    right: 83.33333333%;
  }
  .col-xs-pull-9 {
    right: 75%;
  }
  .col-xs-pull-8 {
    right: 66.66666667%;
  }
  .col-xs-pull-7 {
    right: 58.33333333%;
  }
  .col-xs-pull-6 {
    right: 50%;
  }
  .col-xs-pull-5 {
    right: 41.66666667%;
  }
  .col-xs-pull-4 {
    right: 33.33333333%;
  }
  .col-xs-pull-3 {
    right: 25%;
  }
  .col-xs-pull-2 {
    right: 16.66666667%;
  }
  .col-xs-pull-1 {
    right: 8.33333333%;
  }
  .col-xs-pull-0 {
    right: auto;
  }
  .col-xs-push-12 {
    left: 100%;
  }
  .col-xs-push-11 {
    left: 91.66666667%;
  }
  .col-xs-push-10 {
    left: 83.33333333%;
  }
  .col-xs-push-9 {
    left: 75%;
  }
  .col-xs-push-8 {
    left: 66.66666667%;
  }
  .col-xs-push-7 {
    left: 58.33333333%;
  }
  .col-xs-push-6 {
    left: 50%;
  }
  .col-xs-push-5 {
    left: 41.66666667%;
  }
  .col-xs-push-4 {
    left: 33.33333333%;
  }
  .col-xs-push-3 {
    left: 25%;
  }
  .col-xs-push-2 {
    left: 16.66666667%;
  }
  .col-xs-push-1 {
    left: 8.33333333%;
  }
  .col-xs-push-0 {
    left: auto;
  }
  .col-xs-offset-12 {
    margin-left: 100%;
  }
  .col-xs-offset-11 {
    margin-left: 91.66666667%;
  }
  .col-xs-offset-10 {
    margin-left: 83.33333333%;
  }
  .col-xs-offset-9 {
    margin-left: 75%;
  }
  .col-xs-offset-8 {
    margin-left: 66.66666667%;
  }
  .col-xs-offset-7 {
    margin-left: 58.33333333%;
  }
  .col-xs-offset-6 {
    margin-left: 50%;
  }
  .col-xs-offset-5 {
    margin-left: 41.66666667%;
  }
  .col-xs-offset-4 {
    margin-left: 33.33333333%;
  }
  .col-xs-offset-3 {
    margin-left: 25%;
  }
  .col-xs-offset-2 {
    margin-left: 16.66666667%;
  }
  .col-xs-offset-1 {
    margin-left: 8.33333333%;
  }
  .col-xs-offset-0 {
    margin-left: 0;
  }

  @media print {
    .container-fluid {
      padding-right: 15px;
      padding-left: 15px;
      margin-right: auto;
      margin-left: auto;
    }
    .row {
      margin-right: -15px;
      margin-left: -15px;
    }
    .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
      position: relative;
      min-height: 1px;
      padding-right: 15px;
      padding-left: 15px;
    }
    .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
      float: left;
    }
    .col-xs-12 {
      width: 100%;
    }
    .col-xs-11 {
      width: 91.66666667%;
    }
    .col-xs-10 {
      width: 83.33333333%;
    }
    .col-xs-9 {
      width: 75%;
    }
    .col-xs-8 {
      width: 66.66666667%;
    }
    .col-xs-7 {
      width: 58.33333333%;
    }
    .col-xs-6 {
      width: 50%;
    }
    .col-xs-5 {
      width: 41.66666667%;
    }
    .col-xs-4 {
      width: 33.33333333%;
    }
    .col-xs-3 {
      width: 25%;
    }
    .col-xs-2 {
      width: 16.66666667%;
    }
    .col-xs-1 {
      width: 8.33333333%;
    }
    .col-xs-pull-12 {
      right: 100%;
    }
    .col-xs-pull-11 {
      right: 91.66666667%;
    }
    .col-xs-pull-10 {
      right: 83.33333333%;
    }
    .col-xs-pull-9 {
      right: 75%;
    }
    .col-xs-pull-8 {
      right: 66.66666667%;
    }
    .col-xs-pull-7 {
      right: 58.33333333%;
    }
    .col-xs-pull-6 {
      right: 50%;
    }
    .col-xs-pull-5 {
      right: 41.66666667%;
    }
    .col-xs-pull-4 {
      right: 33.33333333%;
    }
    .col-xs-pull-3 {
      right: 25%;
    }
    .col-xs-pull-2 {
      right: 16.66666667%;
    }
    .col-xs-pull-1 {
      right: 8.33333333%;
    }
    .col-xs-pull-0 {
      right: auto;
    }
    .col-xs-push-12 {
      left: 100%;
    }
    .col-xs-push-11 {
      left: 91.66666667%;
    }
    .col-xs-push-10 {
      left: 83.33333333%;
    }
    .col-xs-push-9 {
      left: 75%;
    }
    .col-xs-push-8 {
      left: 66.66666667%;
    }
    .col-xs-push-7 {
      left: 58.33333333%;
    }
    .col-xs-push-6 {
      left: 50%;
    }
    .col-xs-push-5 {
      left: 41.66666667%;
    }
    .col-xs-push-4 {
      left: 33.33333333%;
    }
    .col-xs-push-3 {
      left: 25%;
    }
    .col-xs-push-2 {
      left: 16.66666667%;
    }
    .col-xs-push-1 {
      left: 8.33333333%;
    }
    .col-xs-push-0 {
      left: auto;
    }
    .col-xs-offset-12 {
      margin-left: 100%;
    }
    .col-xs-offset-11 {
      margin-left: 91.66666667%;
    }
    .col-xs-offset-10 {
      margin-left: 83.33333333%;
    }
    .col-xs-offset-9 {
      margin-left: 75%;
    }
    .col-xs-offset-8 {
      margin-left: 66.66666667%;
    }
    .col-xs-offset-7 {
      margin-left: 58.33333333%;
    }
    .col-xs-offset-6 {
      margin-left: 50%;
    }
    .col-xs-offset-5 {
      margin-left: 41.66666667%;
    }
    .col-xs-offset-4 {
      margin-left: 33.33333333%;
    }
    .col-xs-offset-3 {
      margin-left: 25%;
    }
    .col-xs-offset-2 {
      margin-left: 16.66666667%;
    }
    .col-xs-offset-1 {
      margin-left: 8.33333333%;
    }
    .col-xs-offset-0 {
      margin-left: 0;
    }

    .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
      float: left;
    }
    .col-sm-12 {
      width: 100%;
    }
    .col-sm-11 {
      width: 91.66666667%;
    }
    .col-sm-10 {
      width: 83.33333333%;
    }
    .col-sm-9 {
      width: 75%;
    }
    .col-sm-8 {
      width: 66.66666667%;
    }
    .col-sm-7 {
      width: 58.33333333%;
    }
    .col-sm-6 {
      width: 50%;
    }
    .col-sm-5 {
      width: 41.66666667%;
    }
    .col-sm-4 {
      width: 33.33333333%;
    }
    .col-sm-3 {
      width: 25%;
    }
    .col-sm-2 {
      width: 16.66666667%;
    }
    .col-sm-1 {
      width: 8.33333333%;
    }
    .col-sm-pull-12 {
      right: 100%;
    }
    .col-sm-pull-11 {
      right: 91.66666667%;
    }
    .col-sm-pull-10 {
      right: 83.33333333%;
    }
    .col-sm-pull-9 {
      right: 75%;
    }
    .col-sm-pull-8 {
      right: 66.66666667%;
    }
    .col-sm-pull-7 {
      right: 58.33333333%;
    }
    .col-sm-pull-6 {
      right: 50%;
    }
    .col-sm-pull-5 {
      right: 41.66666667%;
    }
    .col-sm-pull-4 {
      right: 33.33333333%;
    }
    .col-sm-pull-3 {
      right: 25%;
    }
    .col-sm-pull-2 {
      right: 16.66666667%;
    }
    .col-sm-pull-1 {
      right: 8.33333333%;
    }
    .col-sm-pull-0 {
      right: auto;
    }
    .col-sm-push-12 {
      left: 100%;
    }
    .col-sm-push-11 {
      left: 91.66666667%;
    }
    .col-sm-push-10 {
      left: 83.33333333%;
    }
    .col-sm-push-9 {
      left: 75%;
    }
    .col-sm-push-8 {
      left: 66.66666667%;
    }
    .col-sm-push-7 {
      left: 58.33333333%;
    }
    .col-sm-push-6 {
      left: 50%;
    }
    .col-sm-push-5 {
      left: 41.66666667%;
    }
    .col-sm-push-4 {
      left: 33.33333333%;
    }
    .col-sm-push-3 {
      left: 25%;
    }
    .col-sm-push-2 {
      left: 16.66666667%;
    }
    .col-sm-push-1 {
      left: 8.33333333%;
    }
    .col-sm-push-0 {
      left: auto;
    }
    .col-sm-offset-12 {
      margin-left: 100%;
    }
    .col-sm-offset-11 {
      margin-left: 91.66666667%;
    }
    .col-sm-offset-10 {
      margin-left: 83.33333333%;
    }
    .col-sm-offset-9 {
      margin-left: 75%;
    }
    .col-sm-offset-8 {
      margin-left: 66.66666667%;
    }
    .col-sm-offset-7 {
      margin-left: 58.33333333%;
    }
    .col-sm-offset-6 {
      margin-left: 50%;
    }
    .col-sm-offset-5 {
      margin-left: 41.66666667%;
    }
    .col-sm-offset-4 {
      margin-left: 33.33333333%;
    }
    .col-sm-offset-3 {
      margin-left: 25%;
    }
    .col-sm-offset-2 {
      margin-left: 16.66666667%;
    }
    .col-sm-offset-1 {
      margin-left: 8.33333333%;
    }
    .col-sm-offset-0 {
      margin-left: 0;
    }
  }
  @media (min-width: 768px) {
    .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
      float: left;
    }
    .col-sm-12 {
      width: 100%;
    }
    .col-sm-11 {
      width: 91.66666667%;
    }
    .col-sm-10 {
      width: 83.33333333%;
    }
    .col-sm-9 {
      width: 75%;
    }
    .col-sm-8 {
      width: 66.66666667%;
    }
    .col-sm-7 {
      width: 58.33333333%;
    }
    .col-sm-6 {
      width: 50%;
    }
    .col-sm-5 {
      width: 41.66666667%;
    }
    .col-sm-4 {
      width: 33.33333333%;
    }
    .col-sm-3 {
      width: 25%;
    }
    .col-sm-2 {
      width: 16.66666667%;
    }
    .col-sm-1 {
      width: 8.33333333%;
    }
    .col-sm-pull-12 {
      right: 100%;
    }
    .col-sm-pull-11 {
      right: 91.66666667%;
    }
    .col-sm-pull-10 {
      right: 83.33333333%;
    }
    .col-sm-pull-9 {
      right: 75%;
    }
    .col-sm-pull-8 {
      right: 66.66666667%;
    }
    .col-sm-pull-7 {
      right: 58.33333333%;
    }
    .col-sm-pull-6 {
      right: 50%;
    }
    .col-sm-pull-5 {
      right: 41.66666667%;
    }
    .col-sm-pull-4 {
      right: 33.33333333%;
    }
    .col-sm-pull-3 {
      right: 25%;
    }
    .col-sm-pull-2 {
      right: 16.66666667%;
    }
    .col-sm-pull-1 {
      right: 8.33333333%;
    }
    .col-sm-pull-0 {
      right: auto;
    }
    .col-sm-push-12 {
      left: 100%;
    }
    .col-sm-push-11 {
      left: 91.66666667%;
    }
    .col-sm-push-10 {
      left: 83.33333333%;
    }
    .col-sm-push-9 {
      left: 75%;
    }
    .col-sm-push-8 {
      left: 66.66666667%;
    }
    .col-sm-push-7 {
      left: 58.33333333%;
    }
    .col-sm-push-6 {
      left: 50%;
    }
    .col-sm-push-5 {
      left: 41.66666667%;
    }
    .col-sm-push-4 {
      left: 33.33333333%;
    }
    .col-sm-push-3 {
      left: 25%;
    }
    .col-sm-push-2 {
      left: 16.66666667%;
    }
    .col-sm-push-1 {
      left: 8.33333333%;
    }
    .col-sm-push-0 {
      left: auto;
    }
    .col-sm-offset-12 {
      margin-left: 100%;
    }
    .col-sm-offset-11 {
      margin-left: 91.66666667%;
    }
    .col-sm-offset-10 {
      margin-left: 83.33333333%;
    }
    .col-sm-offset-9 {
      margin-left: 75%;
    }
    .col-sm-offset-8 {
      margin-left: 66.66666667%;
    }
    .col-sm-offset-7 {
      margin-left: 58.33333333%;
    }
    .col-sm-offset-6 {
      margin-left: 50%;
    }
    .col-sm-offset-5 {
      margin-left: 41.66666667%;
    }
    .col-sm-offset-4 {
      margin-left: 33.33333333%;
    }
    .col-sm-offset-3 {
      margin-left: 25%;
    }
    .col-sm-offset-2 {
      margin-left: 16.66666667%;
    }
    .col-sm-offset-1 {
      margin-left: 8.33333333%;
    }
    .col-sm-offset-0 {
      margin-left: 0;
    }
  }
</style>
