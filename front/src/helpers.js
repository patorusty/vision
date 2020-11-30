export const helpers = {
  methods: {
    localidadesText(item) {
      return `${item.nombre} - ${item.codigo_postal}`;
    },
    textoActivo(nro) {
      return nro === 1 || nro === true ? "Activo" : "Inactivo";
    },
    codigoText(item) {
      return `${item.apellido}  ${item.nombre} (Matricula: ${item.matricula})`;
    },
    codigoOrgText(item) {
      return `${item.organizadores.apellido}  ${item.organizadores.nombre} (C.Org:  ${item.codigo_organizador})`;
    }
  }
};
