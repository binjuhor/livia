import{_ as c}from"./AppLayout.8b15e2d7.js";import{D as d,o as t,c as l,w as a,a as e,e as s,F as _,h,t as m,b as x,i as f}from"./app.dc95d11d.js";import"./_plugin-vue_export-helper.cdc0426e.js";const u=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Dashboard ",-1),p={class:"py-12"},v={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},g={class:"bg-white overflow-hidden shadow-xl sm:rounded-lg"},w={key:0},y={class:"text-gray-600"},b={class:"flex items-center"},k=f(" View "),j={key:1},D=e("div",{class:"flex items-center justify-between"}," We have no project at the moment. ",-1),B=[D],A={__name:"Dashboard",props:{projects:Array},setup(i){return(n,V)=>{const r=d("inertia-link");return t(),l(c,{title:"Dashboard"},{header:a(()=>[u]),default:a(()=>[e("div",p,[e("div",v,[e("div",g,[i.projects.length>0?(t(),s("div",w,[(t(!0),s(_,null,h(i.projects,o=>(t(),s("div",{class:"flex items-center justify-between",key:o.id},[e("div",y,m(o.name),1),e("div",b,[x(r,{href:n.route("projects.view",o),class:"cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"},{default:a(()=>[k]),_:2},1032,["href"])])]))),128))])):(t(),s("div",j,B))])])])]),_:1})}}};export{A as default};