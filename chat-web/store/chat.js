export const state = () => ({
  rooms: [],
  activeRoom: {},
  messages: [],
  typing: null
});
const underscore = require("underscore");
const moment = require("moment");
export const mutations = {
  createRooms: (state, rooms) => {
    state.rooms = underscore.sortBy(rooms, item => {
      return -moment(item.message_send_at).valueOf();
    });
  },
  addRoom: (state, room) => {
    state.rooms.unshift(room);
  },
  setActive: (state, room) => {
    state.activeRoom = room;
  },
  setMessages: (state, messages) => {
    state.messages = messages;
  },
  addMessage: (state, message) => {
    state.messages.push(message);
  },
  updateLastMessage: (state, room) => {
    state.rooms.forEach(item => {
      if (item.id == room.id) {
        item.message_send_at = room.message_send_at;
        item.last_message = room.last_message;
      }
    });
    state.rooms = underscore.sortBy(state.rooms, item => {
      return -moment(item.message_send_at).valueOf();
    });
  },
  resetData: (state, payload = {}) => {
    state.rooms = [];
    state.activeRoom = {};
    state.messages = [];
  },
  type(state, text) {
    state.typing = text;
  }
};
