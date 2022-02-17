import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";
import moment from "moment";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("test_type.name", "Tipo de Test"),
    c("patient.fullname", "Paciente"),
    c("doctor.fullname", "Doctor"),
    c("", "Compañía")
      .extend({
        html:true,
      })
      .format(function (row){
        if (row.company.name) return `<span> ${row.company.name} </span>`;
        else return `<span> NO APLICA </span>`;;
      })
    ,

    c("result", "Resultado"),
    c("observations", "Observaciones"),

    c("taken_at", "Tomado el").format((value, { row }) =>
      moment(value).format("YYYY MM DD h:mm A")
    ),
    c("result_at", "Resultado el").format((value, { row }) =>
      moment(value).format("YYYY MM DD h:mm A")
    ),

    {
      type: cells.Buttons,
      buttons: [
        {
          label: "Editar",
          clicked({ row }) {
            const url = route("tests.edit", row.id);
            Inertia.visit(url);
          },
        },
      ],
    },
    c("", "")
    .extend({
      html: true,
    })
    .format((value, {row}) => {

      return `<span class="text-black px-2 rounded" style="border: 1px solid red"><a href="/downloadPDF/${row.id}"> PDF  </a> </span>`;
    }),
  ];

  return { rows, cols };
};
