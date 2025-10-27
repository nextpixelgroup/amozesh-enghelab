import { Head, Link, usePage, useForm, router, createInertiaApp } from "@inertiajs/vue3";
import { defineComponent, useSSRContext, resolveComponent, withCtx, unref, createTextVNode, createVNode, renderSlot, ref, withModifiers, mergeProps, createSSRApp, h } from "vue";
import { ssrRenderComponent, ssrRenderSlot, ssrRenderAttrs, ssrRenderStyle } from "vue/server-renderer";
import { route } from "ziggy-js";
import createServer from "@inertiajs/vue3/server";
import { renderToString } from "@vue/server-renderer";
const _export_sfc = (sfc, props) => {
  const target = sfc.__vccOpts || sfc;
  for (const [key, val] of props) {
    target[key] = val;
  }
  return target;
};
const _sfc_main$j = defineComponent({
  components: { Head }
});
function _sfc_ssrRender$h(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  _push(`<!--[-->`);
  _push(ssrRenderComponent(_component_Head, { title: "Login" }, null, _parent));
  _push(`<h1>Login</h1><!--]-->`);
}
const _sfc_setup$j = _sfc_main$j.setup;
_sfc_main$j.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Auth/Login.vue");
  return _sfc_setup$j ? _sfc_setup$j(props, ctx) : void 0;
};
const Login$1 = /* @__PURE__ */ _export_sfc(_sfc_main$j, [["ssrRender", _sfc_ssrRender$h]]);
const __vite_glob_0_0 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Login$1
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$i = {
  __name: "AdminLayout",
  __ssrInlineRender: true,
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      const _component_v_app = resolveComponent("v-app");
      const _component_v_main = resolveComponent("v-main");
      _push(ssrRenderComponent(_component_v_app, _attrs, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="admin-layout"${_scopeId}><header class="bg-gray-800 text-white p-4"${_scopeId}><nav class="container mx-auto flex justify-between items-center"${_scopeId}><h1 class="text-xl font-bold"${_scopeId}>پنل مدیریت</h1><div class="space-x-4"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Link), {
              href: "/admin/dashboard",
              class: "hover:text-gray-300 mask-b-from-lime-400 mr-4"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`داشبورد`);
                } else {
                  return [
                    createTextVNode("داشبورد")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Link), {
              href: "/admin/courses",
              class: "hover:text-gray-300"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`دروس`);
                } else {
                  return [
                    createTextVNode("دروس")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Link), {
              href: "/admin/users",
              class: "hover:text-gray-300"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`کاربران`);
                } else {
                  return [
                    createTextVNode("کاربران")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div></nav></header>`);
            _push2(ssrRenderComponent(_component_v_main, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<main class="container mx-auto p-4"${_scopeId2}>`);
                  ssrRenderSlot(_ctx.$slots, "default", {}, null, _push3, _parent3, _scopeId2);
                  _push3(`</main>`);
                } else {
                  return [
                    createVNode("main", { class: "container mx-auto p-4" }, [
                      renderSlot(_ctx.$slots, "default")
                    ])
                  ];
                }
              }),
              _: 3
            }, _parent2, _scopeId));
            _push2(`<footer class="bg-gray-100 p-4 mt-8"${_scopeId}><div class="container mx-auto text-center text-gray-600"${_scopeId}> تمامی حقوق محفوظ است © 2025 </div></footer></div>`);
          } else {
            return [
              createVNode("div", { class: "admin-layout" }, [
                createVNode("header", { class: "bg-gray-800 text-white p-4" }, [
                  createVNode("nav", { class: "container mx-auto flex justify-between items-center" }, [
                    createVNode("h1", { class: "text-xl font-bold" }, "پنل مدیریت"),
                    createVNode("div", { class: "space-x-4" }, [
                      createVNode(unref(Link), {
                        href: "/admin/dashboard",
                        class: "hover:text-gray-300 mask-b-from-lime-400 mr-4"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("داشبورد")
                        ]),
                        _: 1
                      }),
                      createVNode(unref(Link), {
                        href: "/admin/courses",
                        class: "hover:text-gray-300"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("دروس")
                        ]),
                        _: 1
                      }),
                      createVNode(unref(Link), {
                        href: "/admin/users",
                        class: "hover:text-gray-300"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("کاربران")
                        ]),
                        _: 1
                      })
                    ])
                  ])
                ]),
                createVNode(_component_v_main, null, {
                  default: withCtx(() => [
                    createVNode("main", { class: "container mx-auto p-4" }, [
                      renderSlot(_ctx.$slots, "default")
                    ])
                  ]),
                  _: 3
                }),
                createVNode("footer", { class: "bg-gray-100 p-4 mt-8" }, [
                  createVNode("div", { class: "container mx-auto text-center text-gray-600" }, " تمامی حقوق محفوظ است © 2025 ")
                ])
              ])
            ];
          }
        }),
        _: 3
      }, _parent));
    };
  }
};
const _sfc_setup$i = _sfc_main$i.setup;
_sfc_main$i.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AdminLayout.vue");
  return _sfc_setup$i ? _sfc_setup$i(props, ctx) : void 0;
};
const _sfc_main$h = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$g(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$h = _sfc_main$h.setup;
_sfc_main$h.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Books/Create.vue");
  return _sfc_setup$h ? _sfc_setup$h(props, ctx) : void 0;
};
const Create$3 = /* @__PURE__ */ _export_sfc(_sfc_main$h, [["ssrRender", _sfc_ssrRender$g]]);
const __vite_glob_0_1 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Create$3
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$g = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$f(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$g = _sfc_main$g.setup;
_sfc_main$g.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Books/Edit.vue");
  return _sfc_setup$g ? _sfc_setup$g(props, ctx) : void 0;
};
const Edit$3 = /* @__PURE__ */ _export_sfc(_sfc_main$g, [["ssrRender", _sfc_ssrRender$f]]);
const __vite_glob_0_2 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Edit$3
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$f = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$e(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$f = _sfc_main$f.setup;
_sfc_main$f.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Books/List.vue");
  return _sfc_setup$f ? _sfc_setup$f(props, ctx) : void 0;
};
const List$4 = /* @__PURE__ */ _export_sfc(_sfc_main$f, [["ssrRender", _sfc_ssrRender$e]]);
const __vite_glob_0_3 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: List$4
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$e = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$d(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$e = _sfc_main$e.setup;
_sfc_main$e.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Courses/Create.vue");
  return _sfc_setup$e ? _sfc_setup$e(props, ctx) : void 0;
};
const Create$2 = /* @__PURE__ */ _export_sfc(_sfc_main$e, [["ssrRender", _sfc_ssrRender$d]]);
const __vite_glob_0_4 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Create$2
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$d = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$c(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$d = _sfc_main$d.setup;
_sfc_main$d.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Courses/Edit.vue");
  return _sfc_setup$d ? _sfc_setup$d(props, ctx) : void 0;
};
const Edit$2 = /* @__PURE__ */ _export_sfc(_sfc_main$d, [["ssrRender", _sfc_ssrRender$c]]);
const __vite_glob_0_5 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Edit$2
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$c = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$b(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$c = _sfc_main$c.setup;
_sfc_main$c.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Courses/List.vue");
  return _sfc_setup$c ? _sfc_setup$c(props, ctx) : void 0;
};
const List$3 = /* @__PURE__ */ _export_sfc(_sfc_main$c, [["ssrRender", _sfc_ssrRender$b]]);
const __vite_glob_0_6 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: List$3
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$b = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$a(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$b = _sfc_main$b.setup;
_sfc_main$b.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Orders/List.vue");
  return _sfc_setup$b ? _sfc_setup$b(props, ctx) : void 0;
};
const List$2 = /* @__PURE__ */ _export_sfc(_sfc_main$b, [["ssrRender", _sfc_ssrRender$a]]);
const __vite_glob_0_7 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: List$2
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$a = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$9(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$a = _sfc_main$a.setup;
_sfc_main$a.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Paths/Create.vue");
  return _sfc_setup$a ? _sfc_setup$a(props, ctx) : void 0;
};
const Create$1 = /* @__PURE__ */ _export_sfc(_sfc_main$a, [["ssrRender", _sfc_ssrRender$9]]);
const __vite_glob_0_8 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Create$1
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$9 = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$8(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$9 = _sfc_main$9.setup;
_sfc_main$9.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Paths/Edit.vue");
  return _sfc_setup$9 ? _sfc_setup$9(props, ctx) : void 0;
};
const Edit$1 = /* @__PURE__ */ _export_sfc(_sfc_main$9, [["ssrRender", _sfc_ssrRender$8]]);
const __vite_glob_0_9 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Edit$1
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$8 = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$7(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$8 = _sfc_main$8.setup;
_sfc_main$8.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Paths/List.vue");
  return _sfc_setup$8 ? _sfc_setup$8(props, ctx) : void 0;
};
const List$1 = /* @__PURE__ */ _export_sfc(_sfc_main$8, [["ssrRender", _sfc_ssrRender$7]]);
const __vite_glob_0_10 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: List$1
}, Symbol.toStringTag, { value: "Module" }));
function showNotify(message, type = "positive", handlerFunc) {
  alert(message);
}
function formHandler(initialValues = null, method = "post") {
  const isLoading = ref(false);
  usePage();
  const form = useForm(initialValues);
  const submitForm = (routeName, params = null, goToUrl = "") => {
    return new Promise((resolve, reject) => {
      isLoading.value = true;
      const requestMethod = method === "post" ? form.post : form.put;
      requestMethod.call(form, route(routeName, params), {
        preserveScroll: true,
        preserveState: true,
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        onSuccess: (page) => {
          isLoading.value = false;
          if (page.props.flash?.success && page.props.flash?.success?.message?.length > 0) {
            showNotify(page.props.flash.success.message);
          }
          window.formData = page.props.flash.success.data;
          if (goToUrl.length > 0) {
            goToRoute(goToUrl);
          }
          resolve();
        },
        onError: (errors) => {
          isLoading.value = false;
          showNotify(errors.message || "خطا رخ داد", "negative");
          reject(errors);
        }
      });
    });
  };
  return { form, submitForm, isLoading };
}
const goToRoute = (url) => {
  router.visit(url, {
    preserveScroll: true
  });
};
const _sfc_main$7 = {
  methods: { route },
  components: { AdminLayout: _sfc_main$i, Head },
  setup() {
    usePage();
    const { form, submitForm, isLoading } = formHandler({
      name: ref(""),
      email: ref("")
    });
    const addUser = () => {
      submitForm("admin.users.store");
    };
    return { form, submitForm, isLoading, addUser };
  }
};
function _sfc_ssrRender$6(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  const _component_v_form = resolveComponent("v-form");
  const _component_v_text_field = resolveComponent("v-text-field");
  const _component_v_btn = resolveComponent("v-btn");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Create User" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Users</h1>`);
        _push2(ssrRenderComponent(_component_v_form, { onSubmit: () => {
        } }, {
          default: withCtx((_2, _push3, _parent3, _scopeId2) => {
            if (_push3) {
              _push3(ssrRenderComponent(_component_v_text_field, {
                type: "text",
                modelValue: $setup.form.name,
                "onUpdate:modelValue": ($event) => $setup.form.name = $event
              }, null, _parent3, _scopeId2));
              _push3(ssrRenderComponent(_component_v_text_field, {
                type: "email",
                modelValue: $setup.form.email,
                "onUpdate:modelValue": ($event) => $setup.form.email = $event
              }, null, _parent3, _scopeId2));
              _push3(ssrRenderComponent(_component_v_btn, {
                type: "submit",
                onClick: $setup.addUser
              }, {
                default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                  if (_push4) {
                    _push4(`Create User`);
                  } else {
                    return [
                      createTextVNode("Create User")
                    ];
                  }
                }),
                _: 1
              }, _parent3, _scopeId2));
            } else {
              return [
                createVNode(_component_v_text_field, {
                  type: "text",
                  modelValue: $setup.form.name,
                  "onUpdate:modelValue": ($event) => $setup.form.name = $event
                }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                createVNode(_component_v_text_field, {
                  type: "email",
                  modelValue: $setup.form.email,
                  "onUpdate:modelValue": ($event) => $setup.form.email = $event
                }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                createVNode(_component_v_btn, {
                  type: "submit",
                  onClick: $setup.addUser
                }, {
                  default: withCtx(() => [
                    createTextVNode("Create User")
                  ]),
                  _: 1
                }, 8, ["onClick"])
              ];
            }
          }),
          _: 1
        }, _parent2, _scopeId));
      } else {
        return [
          createVNode(_component_Head, { title: "Create User" }),
          createVNode("h1", null, "Users"),
          createVNode(_component_v_form, {
            onSubmit: withModifiers(() => {
            }, ["prevent"])
          }, {
            default: withCtx(() => [
              createVNode(_component_v_text_field, {
                type: "text",
                modelValue: $setup.form.name,
                "onUpdate:modelValue": ($event) => $setup.form.name = $event
              }, null, 8, ["modelValue", "onUpdate:modelValue"]),
              createVNode(_component_v_text_field, {
                type: "email",
                modelValue: $setup.form.email,
                "onUpdate:modelValue": ($event) => $setup.form.email = $event
              }, null, 8, ["modelValue", "onUpdate:modelValue"]),
              createVNode(_component_v_btn, {
                type: "submit",
                onClick: $setup.addUser
              }, {
                default: withCtx(() => [
                  createTextVNode("Create User")
                ]),
                _: 1
              }, 8, ["onClick"])
            ]),
            _: 1
          }, 8, ["onSubmit"])
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Users/Create.vue");
  return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
const Create = /* @__PURE__ */ _export_sfc(_sfc_main$7, [["ssrRender", _sfc_ssrRender$6]]);
const __vite_glob_0_11 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Create
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$6 = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$5(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Courses" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Courses</h1><p${_scopeId}>Hello, Courses to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Courses" }),
          createVNode("h1", null, "Courses"),
          createVNode("p", null, "Hello, Courses to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Users/Edit.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const Edit = /* @__PURE__ */ _export_sfc(_sfc_main$6, [["ssrRender", _sfc_ssrRender$5]]);
const __vite_glob_0_12 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Edit
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$5 = defineComponent({
  components: { AdminLayout: _sfc_main$i, Head }
});
function _sfc_ssrRender$4(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_AdminLayout = resolveComponent("AdminLayout");
  const _component_Head = resolveComponent("Head");
  _push(ssrRenderComponent(_component_AdminLayout, _attrs, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(ssrRenderComponent(_component_Head, { title: "Users" }, null, _parent2, _scopeId));
        _push2(`<h1${_scopeId}>Users</h1><p${_scopeId}>Hello, Users to your first Inertia app!</p>`);
      } else {
        return [
          createVNode(_component_Head, { title: "Users" }),
          createVNode("h1", null, "Users"),
          createVNode("p", null, "Hello, Users to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
}
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Users/List.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const List = /* @__PURE__ */ _export_sfc(_sfc_main$5, [["ssrRender", _sfc_ssrRender$4]]);
const __vite_glob_0_13 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: List
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$4 = {
  __name: "AppLayout",
  __ssrInlineRender: true,
  setup(__props) {
    const isMenuOpen = ref(false);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "app-layout min-h-screen flex flex-col" }, _attrs))} data-v-16cce7fd><header class="bg-white shadow-sm" data-v-16cce7fd><div class="container mx-auto px-4 py-4" data-v-16cce7fd><div class="flex justify-between items-center" data-v-16cce7fd>`);
      _push(ssrRenderComponent(unref(Link), {
        href: "/",
        class: "text-xl font-bold text-gray-800"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` آموزشگاه `);
          } else {
            return [
              createTextVNode(" آموزشگاه ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<nav class="hidden md:flex space-x-8" data-v-16cce7fd>`);
      _push(ssrRenderComponent(unref(Link), {
        href: "/",
        class: "text-gray-700 hover:text-blue-600 transition"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` صفحه اصلی `);
          } else {
            return [
              createTextVNode(" صفحه اصلی ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Link), {
        href: "/courses",
        class: "text-gray-700 hover:text-blue-600 transition"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` دوره‌ها `);
          } else {
            return [
              createTextVNode(" دوره‌ها ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Link), {
        href: "/about",
        class: "text-gray-700 hover:text-blue-600 transition"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` درباره ما `);
          } else {
            return [
              createTextVNode(" درباره ما ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Link), {
        href: "/contact",
        class: "text-gray-700 hover:text-blue-600 transition"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` تماس با ما `);
          } else {
            return [
              createTextVNode(" تماس با ما ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</nav><button class="md:hidden text-gray-700 focus:outline-none" data-v-16cce7fd><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-16cce7fd>`);
      if (!isMenuOpen.value) {
        _push(`<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" data-v-16cce7fd></path>`);
      } else {
        _push(`<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" data-v-16cce7fd></path>`);
      }
      _push(`</svg></button></div><div class="md:hidden mt-4 space-y-2 pb-4" style="${ssrRenderStyle(isMenuOpen.value ? null : { display: "none" })}" data-v-16cce7fd>`);
      _push(ssrRenderComponent(unref(Link), {
        href: "/",
        class: "block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` صفحه اصلی `);
          } else {
            return [
              createTextVNode(" صفحه اصلی ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Link), {
        href: "/courses",
        class: "block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` دوره‌ها `);
          } else {
            return [
              createTextVNode(" دوره‌ها ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Link), {
        href: "/about",
        class: "block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` درباره ما `);
          } else {
            return [
              createTextVNode(" درباره ما ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Link), {
        href: "/contact",
        class: "block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` تماس با ما `);
          } else {
            return [
              createTextVNode(" تماس با ما ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div></header><main class="flex-grow" data-v-16cce7fd>`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</main><footer class="bg-gray-800 text-white py-8" data-v-16cce7fd><div class="container mx-auto px-4" data-v-16cce7fd><div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-v-16cce7fd><div data-v-16cce7fd><h3 class="text-lg font-semibold mb-4" data-v-16cce7fd>درباره ما</h3><p class="text-gray-300" data-v-16cce7fd> آموزشگاه ما با سال‌ها تجربه در زمینه آموزش، بهترین خدمات آموزشی را به شما ارائه می‌دهد. </p></div><div data-v-16cce7fd><h3 class="text-lg font-semibold mb-4" data-v-16cce7fd>لینک‌های سریع</h3><ul class="space-y-2" data-v-16cce7fd><li data-v-16cce7fd>`);
      _push(ssrRenderComponent(unref(Link), {
        href: "/about",
        class: "text-gray-300 hover:text-white"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`درباره ما`);
          } else {
            return [
              createTextVNode("درباره ما")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</li><li data-v-16cce7fd>`);
      _push(ssrRenderComponent(unref(Link), {
        href: "/courses",
        class: "text-gray-300 hover:text-white"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`دوره‌ها`);
          } else {
            return [
              createTextVNode("دوره‌ها")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</li><li data-v-16cce7fd>`);
      _push(ssrRenderComponent(unref(Link), {
        href: "/contact",
        class: "text-gray-300 hover:text-white"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`تماس با ما`);
          } else {
            return [
              createTextVNode("تماس با ما")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</li></ul></div><div data-v-16cce7fd><h3 class="text-lg font-semibold mb-4" data-v-16cce7fd>راه‌های ارتباطی</h3><ul class="space-y-2 text-gray-300" data-v-16cce7fd><li class="flex items-center" data-v-16cce7fd><svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-16cce7fd><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" data-v-16cce7fd></path></svg> info@example.com </li><li class="flex items-center" data-v-16cce7fd><svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-16cce7fd><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" data-v-16cce7fd></path></svg> 021-12345678 </li></ul></div></div><div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400" data-v-16cce7fd><p data-v-16cce7fd>© 2025 آموزشگاه. تمامی حقوق محفوظ است.</p></div></div></footer></div>`);
    };
  }
};
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AppLayout.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const AppLayout = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["__scopeId", "data-v-16cce7fd"]]);
const _sfc_main$3 = defineComponent({
  components: { AppLayout, Head }
});
function _sfc_ssrRender$3(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  const _component_AppLayout = resolveComponent("AppLayout");
  _push(`<!--[-->`);
  _push(ssrRenderComponent(_component_Head, { title: "Contact" }, null, _parent));
  _push(ssrRenderComponent(_component_AppLayout, null, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`<h1${_scopeId}>Contact</h1><p${_scopeId}>Hello, Contact to your first Inertia app!</p>`);
      } else {
        return [
          createVNode("h1", null, "Contact"),
          createVNode("p", null, "Hello, Contact to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
  _push(`<!--]-->`);
}
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/App/Contact.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const Contact = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["ssrRender", _sfc_ssrRender$3]]);
const __vite_glob_0_14 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Contact
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$2 = defineComponent({
  components: { AppLayout, Head }
});
function _sfc_ssrRender$2(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  const _component_AppLayout = resolveComponent("AppLayout");
  _push(`<!--[-->`);
  _push(ssrRenderComponent(_component_Head, { title: "Welcome" }, null, _parent));
  _push(ssrRenderComponent(_component_AppLayout, null, {
    default: withCtx((_, _push2, _parent2, _scopeId) => {
      if (_push2) {
        _push2(`<h1${_scopeId}>Welcome</h1><h2${_scopeId}>Welcome</h2><p${_scopeId}>Hello, welcome to your first Inertia app!</p>`);
      } else {
        return [
          createVNode("h1", null, "Welcome"),
          createVNode("h2", null, "Welcome"),
          createVNode("p", null, "Hello, welcome to your first Inertia app!")
        ];
      }
    }),
    _: 1
  }, _parent));
  _push(`<!--]-->`);
}
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/App/Index.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const Index$1 = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["ssrRender", _sfc_ssrRender$2]]);
const __vite_glob_0_15 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Index$1
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$1 = defineComponent({
  components: { Head }
});
function _sfc_ssrRender$1(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  _push(`<!--[-->`);
  _push(ssrRenderComponent(_component_Head, { title: "Login" }, null, _parent));
  _push(`<h1>Login</h1><!--]-->`);
}
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Panel/Auth/Index.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const Index = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["ssrRender", _sfc_ssrRender$1]]);
const __vite_glob_0_16 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Index
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main = defineComponent({
  components: { Head }
});
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  _push(`<!--[-->`);
  _push(ssrRenderComponent(_component_Head, { title: "Login" }, null, _parent));
  _push(`<h1>Login</h1><!--]-->`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Panel/Auth/Login.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const Login = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender]]);
const __vite_glob_0_17 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Login
}, Symbol.toStringTag, { value: "Module" }));
createServer(
  (page) => createInertiaApp({
    page,
    render: renderToString,
    resolve: (name) => {
      const pages = /* @__PURE__ */ Object.assign({ "./Pages/Admin/Auth/Login.vue": __vite_glob_0_0, "./Pages/Admin/Books/Create.vue": __vite_glob_0_1, "./Pages/Admin/Books/Edit.vue": __vite_glob_0_2, "./Pages/Admin/Books/List.vue": __vite_glob_0_3, "./Pages/Admin/Courses/Create.vue": __vite_glob_0_4, "./Pages/Admin/Courses/Edit.vue": __vite_glob_0_5, "./Pages/Admin/Courses/List.vue": __vite_glob_0_6, "./Pages/Admin/Orders/List.vue": __vite_glob_0_7, "./Pages/Admin/Paths/Create.vue": __vite_glob_0_8, "./Pages/Admin/Paths/Edit.vue": __vite_glob_0_9, "./Pages/Admin/Paths/List.vue": __vite_glob_0_10, "./Pages/Admin/Users/Create.vue": __vite_glob_0_11, "./Pages/Admin/Users/Edit.vue": __vite_glob_0_12, "./Pages/Admin/Users/List.vue": __vite_glob_0_13, "./Pages/App/Contact.vue": __vite_glob_0_14, "./Pages/App/Index.vue": __vite_glob_0_15, "./Pages/Panel/Auth/Index.vue": __vite_glob_0_16, "./Pages/Panel/Auth/Login.vue": __vite_glob_0_17 });
      return pages[`./Pages/${name}.vue`];
    },
    setup({ App, props, plugin }) {
      return createSSRApp({
        render: () => h(App, props)
      }).use(plugin);
    }
  })
);
