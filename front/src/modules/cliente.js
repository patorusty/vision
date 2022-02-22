import http from "../http-request";

const API_URL = '/clientes';

const state = () => ({
    clientes: [],
    cliente: {
        tipo_persona: 1,
        cuit: "",
        nro_dni: "",
        activo: true,
        cuitOriginal: '',
        dniOriginal: ''
    },
    tipos_persona: [
        {
            value: 1,
            text: "Persona Fisica"
        },
        {
            value: 2,
            text: "Persona Juridica"
        }
        ],
    loading: true
});
const mutations = {
    SET_CLIENTES(state, clientes) {
        state.loading = false
        state.clientes = clientes
    },
    SET_CLIENTE(state, cliente) {
        state.cliente = cliente
        state.loading = false
        state.cliente.cuitOriginal = cliente.cuit
        state.cliente.dniOriginal = cliente.nro_dni
    },
    RESET_CLIENTE(state) {
        state.cliente = Object.assign(
            {},
            {
                tipo_persona: 1,
                cuit: "",
                nro_dni: "",
                activo: true,
                cuitOriginal: '',
                dniOriginal: ''
            }
        );
    },
    CREATE_CLIENTE(state, cliente) {
        state.clientes.unshift(cliente);
    },
    UPDATE_CLIENTE(state, cliente) {
        var item = state.clientes.find(item => item.id === cliente.id);
        return Object.assign(item, cliente);
    },
    DELETE_CLIENTE(state, id) {
        state.clientes = state.clientes.filter(c => c.id != id);
    }
};
const actions = {
    async getClientes({ commit }) {
        const resp = await http.get(API_URL);
        commit('SET_CLIENTES', resp.data)
    },
    async getCliente({ commit, dispatch }, id) {
        const resp = await http.getOne(API_URL, id);
        commit('SET_CLIENTE', resp.data)
    },
    async createCliente({ commit }, cliente) {
        const resp = await http.post(API_URL, cliente)
        if (resp.status === 201) {
            commit("CREATE_CLIENTE", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Cliente creado con éxito!"
                },
                { root: true }
            );
            return true;
        } else {
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "red",
                    snackText: "Algo salió mal, intente nuevamente..."
                },
                { root: true }
            );
        }
    },
    async updateCliente({ commit }, cliente) {
        const resp = await http.put(
            API_URL,
            cliente.id,
            cliente
        );
        if (resp.status === 200) {
            commit("UPDATE_CLIENTE", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Cliente editado con éxito!"
                },
                { root: true }
            );
            return true;
        } else {
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "red",
                    snackText: "Algo salió mal, intente nuevamente..."
                },
                { root: true }
            );
        }
    },
    async deleteCliente({ commit }, id) {
        const resp = await http.delete(API_URL, id);
        if (resp.status === 200) {
            commit("DELETE_CLIENTE", id);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Cliente eliminado con éxito!"
                },
                { root: true }
            );
            commit('modal/HIDE_MODAL', false,
                { root: true });
        } else {
            commit(
                "snackbar/SHOW_SNACK", {
                color: "success",
                snackText: "Algo salió mal..."
            },
                { root: true }
            );
        }
    }

};
export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters: {}
};
