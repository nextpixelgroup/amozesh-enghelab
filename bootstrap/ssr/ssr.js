import { Head, Link, createInertiaApp } from "@inertiajs/vue3";
import { defineComponent, useSSRContext, resolveComponent, mergeProps, unref, withCtx, createTextVNode, createVNode, ref, createSSRApp, h } from "vue";
import { ssrRenderComponent, ssrRenderAttrs, ssrRenderSlot, ssrRenderStyle } from "vue/server-renderer";
import createServer from "@inertiajs/vue3/server";
import { renderToString } from "@vue/server-renderer";
const _export_sfc = (sfc, props) => {
  const target = sfc.__vccOpts || sfc;
  for (const [key, val] of props) {
    target[key] = val;
  }
  return target;
};
const _sfc_main$7 = defineComponent({
  components: { Head }
});
function _sfc_ssrRender$5(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  _push(`<!--[-->`);
  _push(ssrRenderComponent(_component_Head, { title: "Login" }, null, _parent));
  _push(`<h1>Login</h1><!--]-->`);
}
const _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Auth/Login.vue");
  return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
const Login = /* @__PURE__ */ _export_sfc(_sfc_main$7, [["ssrRender", _sfc_ssrRender$5]]);
const __vite_glob_0_0 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Login
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$6 = {
  __name: "AdminLayout",
  __ssrInlineRender: true,
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "admin-layout" }, _attrs))}><header class="bg-gray-800 text-white p-4"><nav class="container mx-auto flex justify-between items-center"><h1 class="text-xl font-bold">پنل مدیریت</h1><div class="space-x-4">`);
      _push(ssrRenderComponent(unref(Link), {
        href: "/admin/dashboard",
        class: "hover:text-gray-300 mask-b-from-lime-400 mr-4"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`داشبورد`);
          } else {
            return [
              createTextVNode("داشبورد")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Link), {
        href: "/admin/courses",
        class: "hover:text-gray-300"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`دروس`);
          } else {
            return [
              createTextVNode("دروس")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Link), {
        href: "/admin/users",
        class: "hover:text-gray-300"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`کاربران`);
          } else {
            return [
              createTextVNode("کاربران")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></nav></header><main class="container mx-auto p-4">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</main><footer class="bg-gray-100 p-4 mt-8"><div class="container mx-auto text-center text-gray-600"> تمامی حقوق محفوظ است © 2025 </div></footer></div>`);
    };
  }
};
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AdminLayout.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const _sfc_main$5 = defineComponent({
  components: { AdminLayout: _sfc_main$6, Head }
});
function _sfc_ssrRender$4(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
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
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Courses/List.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const List$1 = /* @__PURE__ */ _export_sfc(_sfc_main$5, [["ssrRender", _sfc_ssrRender$4]]);
const __vite_glob_0_1 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: List$1
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$4 = defineComponent({
  components: { AdminLayout: _sfc_main$6, Head }
});
function _sfc_ssrRender$3(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
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
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Users/List.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const List = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["ssrRender", _sfc_ssrRender$3]]);
const __vite_glob_0_2 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: List
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$3 = {
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
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AppLayout.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const AppLayout = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["__scopeId", "data-v-16cce7fd"]]);
const _sfc_main$2 = defineComponent({
  components: { AppLayout, Head }
});
function _sfc_ssrRender$2(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
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
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/App/Contact.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const Contact = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["ssrRender", _sfc_ssrRender$2]]);
const __vite_glob_0_3 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Contact
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main$1 = defineComponent({
  components: { AppLayout, Head }
});
function _sfc_ssrRender$1(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
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
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/App/Index.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const Index$1 = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["ssrRender", _sfc_ssrRender$1]]);
const __vite_glob_0_4 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Index$1
}, Symbol.toStringTag, { value: "Module" }));
const _sfc_main = defineComponent({
  components: { Head }
});
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_Head = resolveComponent("Head");
  _push(`<!--[-->`);
  _push(ssrRenderComponent(_component_Head, { title: "Auth" }, null, _parent));
  _push(`<h1>Auth</h1><!--]-->`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Panel/Auth/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const Index = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender]]);
const __vite_glob_0_5 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: Index
}, Symbol.toStringTag, { value: "Module" }));
createServer((page) => createInertiaApp({
  page,
  render: renderToString,
  resolve: async (name) => {
    const pages = /* @__PURE__ */ Object.assign({ "./Pages/Admin/Auth/Login.vue": __vite_glob_0_0, "./Pages/Admin/Courses/List.vue": __vite_glob_0_1, "./Pages/Admin/Users/List.vue": __vite_glob_0_2, "./Pages/App/Contact.vue": __vite_glob_0_3, "./Pages/App/Index.vue": __vite_glob_0_4, "./Pages/Panel/Auth/Index.vue": __vite_glob_0_5 });
    const path = `./Pages/${name}.vue`;
    if (!pages[path]) {
      throw new Error(`Page not found: ${path}`);
    }
    const page2 = pages[path].default;
    const noSsrPaths = ["admin", "panel"];
    const shouldDisableSsr = noSsrPaths.some(
      (prefix) => name.toLowerCase().startsWith(prefix.toLowerCase() + "/") || name.toLowerCase() === prefix.toLowerCase()
    );
    if (shouldDisableSsr) {
      page2.ssr = false;
      console.log(`SSR disabled for: ${name}`);
    } else {
      console.log(`SSR enable for: ${name}`);
    }
    return page2;
  },
  setup({ el, App, props, plugin }) {
    return createSSRApp({
      render: () => h(App, props)
    }).use(plugin);
  }
}));
