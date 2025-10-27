const Ziggy = {"url":"http:\/\/localhost:8000","port":8000,"defaults":{},"routes":{"admin.login":{"uri":"admin\/login","methods":["GET","HEAD"]},"admin.index":{"uri":"admin\/books","methods":["GET","HEAD"]},"admin.":{"uri":"admin\/users","methods":["GET","HEAD"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
