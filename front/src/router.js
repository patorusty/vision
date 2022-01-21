import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

export default new Router({
  mode: "history",
  base: process.env.BASE_URL,
  routes: [
    // {
    //   path: "/polizas",
    //   // component: () => import('@/views/polizas/Automotor'),
    //   children: []
    // },
    {
      path: "/",
      component: () => import("@/views/dashboard/Index"),
      children: [

        // Dashboard
        {
          name: "Polizas",
          path: "polizas",
          component: () => import("@/views/pages/Polizas/Polizas.vue")
        },
        {
          name: "Crear Poliza",
          path: "polizas/automotor/create",
          component: () => import("@/views/pages/Polizas/CreatePoliza.vue")
        },
        {
          name: "Editar Poliza",
          path: "polizas/automotor/:numero_solicitud",
          component: () => import("@/views/pages/Polizas/EditPoliza.vue")
        },
        {
          name: "Compañías",
          path: "administracion/companias",
          component: () => import(
            "@/views/pages/Administracion/Companias/Companias.vue"
          )
        },
        {
          name: "Editar Compañía",
          path: "administracion/companias/:nombre",
          component: () => import(
            "@/views/pages/Administracion/Companias/EditCompania.vue"
          )
        },
        {
          name: "Siniestros",
          path: "siniestros",
          component: () => import(
            "@/views/pages/Siniestros/Siniestros.vue"
          )
        },
        {
          name: "Endosos",
          path: "endosos",
          component: () => import(
            "@/views/pages/Endosos/Endosos.vue"
          )
        },
        {
          name: "Clientes",
          path: "clientes",
          component: () => import(
            "@/views/pages/Clientes/Clientes.vue"
          )
        },
        {
          name: "Crear Cliente",
          path: "clientes/create",
          component: () => import(
            "@/views/pages/Clientes/CreateCliente.vue"
          )
        },
        {
          name: "Editar Cliente",
          path: "clientes/:id",
          component: () => import(
            "@/views/pages/Clientes/EditCliente.vue"
          )
        },
        {
          name: "Organizadores",
          path: "administracion/organizadores",
          component: () =>
            import(
              "@/views/pages/Administracion/Organizadores/Organizadores.vue"
            )
        },
        {
          name: "Productores",
          path: "administracion/productores",
          component: () =>
            import("@/views/pages/Administracion/Productores/Productores.vue")
        },
        {
          name: "Marca/Modelo/Version",
          path: "administracion/mmv",
          component: () =>
            import("@/views/pages/Administracion/MMV/MMV.vue")
        },
        // {
        //   name: "Usuarios",
        //   path: "configuracion/usuarios", 
        //   component: () => import("@/views/pages/Configuracion/Usuarios.vue")
        // },
      ]

    }
  ]
});
