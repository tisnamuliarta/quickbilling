const helper = {
  name: 'Helper',
  mapping(type) {
    switch (type) {
      case 'SQ':
        return 'Sales Quotations';
      case 'SO':
        return 'Sales Order';
      case 'SD':
        return 'Sales Delivery';
      case 'SI':
        return 'A/R Invoice';
      case 'SP':
        return 'Incoming Payment';
      case 'SR':
        return 'Sales Return';
      case 'PQ':
        return 'Purchase Quotations';
      case 'PO':
        return 'Purchase Order';
      case 'PR':
        return 'Goods Receipt';
      case 'PI':
        return 'A/P Invoice';
      case 'PP':
        return 'Outgoing Payment';
      case 'PN':
        return 'Goods Return';
    }
  },

  mappingAction(type) {
    switch (type) {
      case 'SQ':
        return '/dashboard/sales/quote'
      case 'SO':
        return '/dashboard/sales/order'
      case 'SD':
        return '/dashboard/sales/delivery'
      case 'SI':
        return '/dashboard/sales/invoice'
      case 'SP':
        return '/dashboard/sales/payment'
      case 'SR':
        return '/dashboard/sales/return'
      case 'PQ':
        return '/dashboard/purchase/quote'
      case 'PO':
        return '/dashboard/purchase/order'
      case 'PR':
        return '/dashboard/purchase/receipt'
      case 'PI':
        return '/dashboard/purchase/invoice'
      case 'PP':
        return '/dashboard/purchase/payment'
      case 'PN':
        return '/dashboard/purchase/return'
    }
  },

  itemAction(type) {
    switch (type) {
      case 'SQ':
        return [
          {title: 'Create Invoice', action: 'SI', icon: 'mdi-printer'},
          {title: 'Create Order', action: 'SO', icon: 'mdi-email'},
          {title: 'Clone', action: 'SQ', icon: 'mdi-email'},
        ];
      case 'SO':
        return 'sales order';
      case 'SD':
        return 'sales delivery';
      case 'SI':
        return 'A/R invoice';
      case 'SP':
        return 'incoming payment';
      case 'SR':
        return 'sales return';
      case 'PQ':
        return 'purchase quotations';
      case 'PO':
        return 'purchase order';
      case 'PR':
        return 'goods receipt';
      case 'PI':
        return 'A/P invoice';
      case 'PP':
        return 'outgoing payment';
      case 'PN':
        return 'goods return';
    }
  },
}

export default ({app}, inject) => {
  inject('helper', helper)
}
