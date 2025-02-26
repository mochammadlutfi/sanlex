import{g as i,t,i as ae,a as oe,o as te,w as o,d as r,b as e,j as m,f as u,u as l,I as w,E as le,e as b,n as ne,p as se,q as re,l as B}from"./app-BGxfkQyo.js";import{_ as ce,a as me,b as ie,E as v}from"./lodash-B8vTn4c7.js";import{u as ue,E as de,a as pe,S as _e,b as fe}from"./SkeletonTable-D3dCL_hH.js";import{E as ge,a as ve}from"./index-D86IKBYl.js";import{E as be}from"./index-BJIagJY3.js";import{E as he}from"./index-D_ov1zZm.js";import{E as ye,a as Ee}from"./index-CyV8PQJT.js";import{E as we}from"./index-DY15ID-N.js";import{E as ke,a as $e}from"./index-CvhItr7_.js";import{E as Ce}from"./index-DDRuwiU-.js";import"./scroll-B9W2qgUL.js";import"./index-CYh1QxFF.js";import"./validator-DuQ1bq1g.js";import"./index-BPi1rbym.js";import"./directive-Chdceyer.js";const Ve={class:"content"},Se={class:"content-header"},Be={class:"mt-auto mb-0"},De={class:"text-lg font-semibold"},Ie={class:"mt-auto mb-0"},qe={class:"flex justify-between items-center p-4"},Te={class:"flex items-center gap-2"},Fe={class:"flex justify-between items-center p-4"},je={class:"flex items-center gap-2"},Me={class:"flex items-center gap-2"},Pe={class:"text-end"},aa={__name:"Packaging",setup(Re){const d=i({page:1,limit:25,search:""}),T=i([]),F=async({queryKey:a})=>{const[s,_]=a;return(await B.get("/product/packaging/data",{params:_})).data},{data:p,isLoading:k,isError:Ue,error:xe,refetch:f}=ue({queryKey:["fetchData",d.value],queryFn:F,keepPreviousData:!0}),j=ce.debounce(()=>{d.value.page=1,f()},1e3),M=a=>{T.value=a},P=()=>{f()},R=a=>{d.value.page=a,f()},$=i(null),g=i(!1),C=i(""),n=i({id:null,name:null}),U=i({name:[{required:!0,message:t("validation.required",{attribute:t("common.name")}),trigger:"blur"}]}),V=i(!1),x=()=>{C.value=`${t("common.create")} ${t("base.packaging")}`,g.value=!0,n.value.id=null,n.value.name=null},L=a=>{C.value=`${t("common.edit")} ${t("base.packaging")}`,g.value=!0,n.value.id=a.id,n.value.name=a.name},D=()=>{g.value=!1,n.value.id=null,n.value.name=null},N=async()=>{$.value&&$.value.validate(async a=>{if(a){const s=Ce.service({customClass:"rounded-md",target:document.querySelector("#modalForm")});try{V.value=!0;const _=n.value.id?`/product/packaging/${n.value.id}/update`:"/product/packaging/store",h=n.value.id?"put":"post";await B({method:h,url:_,data:n.value}),V.value=!1,g.value=!1,f(),D(),v({message:t("message.success_save"),type:"success"})}catch{V.value=!1,v({message:t("message.error_server"),type:"error"})}s.close()}else v({message:t("message.error_input"),type:"error"})})},z=a=>{fe.confirm(t("message.delete_confirm"),t("message.delete_confirm_title"),{confirmButtonText:t("common.ok"),cancelButtonText:t("common.cancel"),type:"warning"}).then(()=>{B.delete(`/product/packaging/${a}/delete`).then(()=>{f(),v({type:"success",message:t("message.delete_success")})}).catch(s=>{v({type:"error",message:t("message.delete_cancel")})})}).catch(()=>{v({type:"info",message:t("message.delete_cancel")})})};return(a,s)=>{const _=ve,h=ge,S=ie,K=me,I=he,y=le,E=Ee,q=re,O=se,Q=ne,A=ye,G=pe,H=de,J=be,W=$e,X=ke,Y=we,Z=ae("base-layout");return te(),oe(Z,null,{default:o(()=>[r("div",Ve,[r("div",Se,[r("div",Be,[r("div",De,m(a.$t("base.packaging")),1)]),r("div",Ie,[e(h,{separator:"/"},{default:o(()=>[e(_,{to:{path:"/dashboard"}},{default:o(()=>[u(m(a.$t("base.dashboard")),1)]),_:1}),e(_,null,{default:o(()=>[u(m(a.$t("base.product")),1)]),_:1})]),_:1})])]),e(J,{"body-class":"!p-0",class:"!rounded-lg !shadow-md !mb-10"},{default:o(()=>[r("div",qe,[e(K,{modelValue:d.value.limit,"onUpdate:modelValue":s[0]||(s[0]=c=>d.value.limit=c),placeholder:a.$t("common.select"),class:"w-24",onChange:l(f),disable:l(k)},{default:o(()=>[e(S,{label:"25",value:"25"}),e(S,{label:"50",value:"50"}),e(S,{label:"100",value:"100"})]),_:1},8,["modelValue","placeholder","onChange","disable"]),r("div",Te,[e(I,{modelValue:d.value.search,"onUpdate:modelValue":s[1]||(s[1]=c=>d.value.search=c),clearable:"",onInput:l(j),disable:l(k)},{prefix:o(()=>[e(l(w),{icon:"mingcute:search-line"})]),_:1},8,["modelValue","onInput","disable"]),e(y,{type:"primary",onClick:b(x,["prevent"])},{default:o(()=>[e(l(w),{icon:"mingcute:add-line",class:"me-2"}),u(" "+m(a.$t("common.create")),1)]),_:1})])]),e(H,{loading:l(k),animated:""},{template:o(()=>[e(_e)]),default:o(()=>[e(A,{class:"min-w-full",data:l(p).data,onSortChange:P,onSelectionChange:M},{default:o(()=>[e(E,{type:"selection",width:"55"}),e(E,{prop:"name",label:a.$t("common.name"),sortable:""},null,8,["label"]),e(E,{prop:"product_count",label:a.$t("common.total_data",{data:a.$t("base.product")}),sortable:""},null,8,["label"]),e(E,{label:a.$t("common.action"),align:"center",width:"150"},{default:o(c=>[e(Q,{"popper-class":"dropdown-action",trigger:"click"},{dropdown:o(()=>[e(O,null,{default:o(()=>[e(q,{class:"flex justify-between",onClick:b(ee=>L(c.row),["prevent"])},{default:o(()=>[e(l(w),{icon:"mingcute:edit-line",class:"me-2"}),u(" "+m(a.$t("common.edit")),1)]),_:2},1032,["onClick"]),e(q,{class:"flex justify-between",onClick:b(ee=>z(c.row.id),["prevent"])},{default:o(()=>[e(l(w),{icon:"mingcute:delete-2-line",class:"me-2"}),u(" "+m(a.$t("common.delete")),1)]),_:2},1032,["onClick"])]),_:2},1024)]),default:o(()=>[e(y,{type:"primary"},{default:o(()=>[u(m(a.$t("common.action")),1)]),_:1})]),_:2},1024)]),_:1},8,["label"])]),_:1},8,["data"]),r("div",Fe,[r("div",je,[r("span",null,m(a.$t("common.table_paginate",{from:l(p).from,to:l(p).to,total:l(p).total})),1)]),r("div",Me,[e(G,{class:"float-end",background:"",layout:"prev, pager, next","page-size":l(p).per_page,total:l(p).total,"current-page":l(p).current_page,onCurrentChange:R},null,8,["page-size","total","current-page"])])])]),_:1},8,["loading"])]),_:1}),e(Y,{id:"modalForm",modelValue:g.value,"onUpdate:modelValue":s[3]||(s[3]=c=>g.value=c),title:C.value,class:"!sm:w-full !w-1/3 rounded-lg","close-on-click-modal":!1,"close-on-press-escape":!1},{default:o(()=>[e(X,{"label-position":"top",ref_key:"formRef",ref:$,model:n.value,rules:U.value,onSubmit:b(N,["prevent"])},{default:o(()=>[e(W,{label:a.$t("common.name"),prop:"name"},{default:o(()=>[e(I,{modelValue:n.value.name,"onUpdate:modelValue":s[2]||(s[2]=c=>n.value.name=c)},null,8,["modelValue"])]),_:1},8,["label"]),r("div",Pe,[e(y,{onClick:b(D,["prevent"])},{default:o(()=>[u(m(a.$t("common.cancel")),1)]),_:1}),e(y,{type:"primary","native-type":"submit"},{default:o(()=>[u(m(a.$t("common.save")),1)]),_:1})])]),_:1},8,["model","rules"])]),_:1},8,["modelValue","title"])])]),_:1})}}};export{aa as default};
