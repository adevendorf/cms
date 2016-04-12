import {
  SET_MODAL_TO,
} from '../mutation-types'

const state = {
  component: null
}

const mutations = {
  [SET_MODAL_TO] (state, newComponent) {
    state.component = newComponent;
  }
}

export default {
  state,
  mutations
}