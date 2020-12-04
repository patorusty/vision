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
          name: "Automotor",
          path: "polizas/automotor",
          component: () => import("@/views/pages/Polizas/Automotor")
        },
        {
          name: "OtrosRiesgos",
          path: "polizas/otrosriesgos",
          component: () => import("@/views/pages/Polizas/OtrosRiesgos")
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
          name: "Usuarios",
          path: "configuracion/usuarios",
          component: () => import("@/views/pages/configuracion/Usuarios")
        },
      ]

    }
  ]
});
