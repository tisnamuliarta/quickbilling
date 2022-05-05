const formatter = {
  name: 'Formatter',
  formatPrice(value) {
    let val = (value / 1).toFixed(2).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
  },

  formatCheckBox(value) {
    if (value === '1') {
      return 'On'
    } else if (value === '0') {
      return 'Off'
    } else {
      if (value) {
        return 'On'
      } else {
        return 'Off'
      }
    }
  },
}

export default ({app}, inject) => {
  inject('formatter', formatter)
}
