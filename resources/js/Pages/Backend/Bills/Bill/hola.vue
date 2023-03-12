<template></template>
<script>
export default {
  organizeBill() {
    let oldBills = this.bills;
    const arrBills = [];
    for (let i = 0; i < oldBill.length; i++) {
      const newBill = {
        "#": i,
        "Fecha" : dates.dateForLaravel(oldBills[i].created_at),
        "Pagador" : oldBills[i].payer,
        "MetodoPago": oldBills[i].paymentway,
        "OrigenDinero": oldBills[i].moneyOrigin,
        "Descripcion" : oldBills[i].description,
        "Familia" : oldBills[i].bills_sub_family.billsfamily.name,
        "SubFamilia" : oldBills[i].bills_sub_family.name,
        "Receptor" : oldBills[i].receiver,
        "Monto" : oldBills[i].quantity,
        "Usuario de Sistema": oldBills[i].created_by
      };
      arrBills.push(newBill);
    }

    return arrBills;
  },

  exportToExcel() {

  },
};
</script>
