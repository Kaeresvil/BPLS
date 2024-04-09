<template>
    <div>
   <router-view></router-view>
    </div>
</template>

<style>
body {
    background: linear-gradient(rgba(117, 117, 117, 0.7), rgba(68, 68, 68, 0.7)), url('/img/curimao.jpeg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}


.buttonshow {
    background-color:#24792f;
    border-color:#253d28;
    color: white;
}
.buttoncreate {
    background-color: #3c8dbc;
  border-color: #367fa9;
  color: white;
}
.buttondelete{
    background-color: #d64c3a;
  border-color: #9f2d1e;
  color: white;
  margin-left: 2px;
}

:where(.css-dev-only-do-not-override-1hsjdkk).ant-menu-light.ant-menu-horizontal >.ant-menu-item-selected, :where(.css-dev-only-do-not-override-1hsjdkk).ant-menu-light.ant-menu-horizontal >.ant-menu-submenu-selected {
    color: #353739;
    background-color: transparent;
}


</style>