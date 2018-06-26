<?php
$this->registerJs(" var program_id='".$program_id."';",\yii\web\View::POS_HEAD);
$this->registerJsFile("/js/pages/evaluate.js");

//$select_platforms = $platforms;
//$select_properties = $properties;
$select_types = $types;
$select_times = $times;
?>
<?= $this->render('../module/head_tag'); ?>
<div class="wrap">
    <div class="owl-mode">
        <?= $this->render('../module/mode') ?>
    </div>
    <div class="owl-content">
        <?= $this->render('../module/header') ?>
        <div class="content" id="table_filter">
            <div class="pure-u-1-6">
                <h3 class="title">筛选条件</h3></div>
            <div class="pure-u-1-3">
                <input type="text" class="input-label label-search" placeholder="请输入您要搜索的剧目名称" v-model="name_type.value" v-on:keyup.enter="get_list" v-bind:value="name_type.value">
                <button type="button" class="pure-btn btn-small btn-submit" v-on:click="get_list"> 搜 索</button>
            </div>
            <div class="pure-u-1-3">
                <div class="control-item" id="searchType">
                    <div v-on:click="select_type('program_name')" v-bind:class="{'active':name_type.program_name}" class="control-label" value="program_name"><span class="ico-checkbox">剧目名称</span></div>
                    <div v-on:click="select_type('type_name')" v-bind:class="{'active':name_type.type_name}" class="control-label" value="type"><span class="ico-checkbox">内容类型</span></div>
                </div>
            </div>
            <br class="clear">
            <div class="form-eval">
                <div class="filter-box">
                    <label>媒体平台：</label>
                    <template>
                    <div class="filter-group" >
                        <div v-bind:class="{on:selected['platforms'][platform.platform_name]}"  v-on:click="select('platforms',platform.platform_name)" class="filter-item" v-for="platform in platforms" v-bind:value="platform.platform_name">{{platform.platform_name}}</div>
                    </div>
                    </template>
                </div>
                <div class="filter-box">
                    <label>内容属性：</label>
                    <template>
                    <div class="filter-group">
                        <div v-bind:class="{on:selected['properties'][property.property_name]}" v-on:click="select('properties',property.property_name)" class="filter-item" v-for="property in properties" v-bind:value="property.property_name">{{property.property_name}}</div>
                    </div>
                    </template>
                </div>
                <div class="filter-box" v-bind:class="{open:filter_open.type1}">
                    <label>一级类型：</label>
                    <a href="javascript:;" @click="open_box('type1')" class="filter-item-more">更多</a>
                    <template>
                    <div class="filter-group">
                        <div v-bind:class="{on:selected['type1'][type]}" v-on:click="select('type1',type)" class="filter-item" v-for="type in type1" v-bind:value="type">{{type}}</div>
                    </div>
                    </template>
                </div>
                <div class="filter-box" v-bind:class="{open:filter_open.type2}">
                    <label>二级类型：</label>
                    <a href="javascript:;" @click="open_box('type2')" class="filter-item-more">更多</a>
                    <template>
                    <div class="filter-group">
                        <div v-bind:class="{on:selected['type2'][type]}" v-on:click="select('type2',type)" class="filter-item" v-for="type in type2" v-bind:value="type">{{type}}</div>
                    </div>
                    </template>
                </div>
                <div class="filter-box" v-bind:class="{open:filter_open.times}">
                    <label>开播时间：</label>
                    <a href="javascript:;" @click="open_box('time')" class="filter-item-more">更多</a>
                    <template>
                    <div class="filter-group">
                        <div v-bind:class="{on:selected['times'][time.start_play]}" v-on:click="select('times',time.start_play)" class="filter-item" v-for="time in times" v-bind:value="time.start_play">{{time.start_play}}</div>
                    </div>
                    </template>
                </div>
                <div class="text-center">
                    <br>
                    <br>
                    <button v-on:click="get_list" type="button" class="pure-btn btn-large btn-submit" name="btn_search"> 确 定</button>
                    <br>
                </div>
            </div>
        </div>
        <div class="content">
            <div id="has-result">
                <div class="pure-u-1-6">
                    <h3 class="title">可选剧目</h3>
                </div>
                <div class="result-tools">
                    <label for="">显示条目：</label>
                    <div id="table_top" class="pure-btn-group" role="group">
                        <button v-on:click="get_list('top10')" v-bind:class="{'pure-btn-active':top10}" class="pure-btn" value="10">TOP10</button>
                        <button v-on:click="get_list('top20')" v-bind:class="{'pure-btn-active':top20}"  class="pure-btn" value="20">TOP20</button>
                        <button v-on:click="get_list('topall')" v-bind:class="{'pure-btn-active':topall}"  class="pure-btn" value="">全部</button>
                    </div>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <label for="">排序：</label>
                    <div id="table_sort" class="pure-btn-group" role="group">
                        <button v-on:click="get_list('score')" v-bind:class="{'pure-btn-active':score}" class="pure-btn" value="score">得分</button>
                        <button v-on:click="get_list('time')" v-bind:class="{'pure-btn-active':time}" class="pure-btn" value="time">开播时间</button>
                    </div>
                </div>
                <div id="table_list">
                    <div class="result-tags clear">
                        <h4 class="pull-left">已选剧目</h4>
                        <div id="resultTags">
                            <template>
                            <span class="result-tag" v-for="(d,index) in selected">{{d}}<a v-on:click="delete_tag(index)" href="javascript:;" class="ico-close"></a></span>
                            </template>
                        </div>
                    </div>
                    <div class="result-list" id="resultList">
                        <div>
                            <table class="pure-table pure-table-none pure-table-line">
                                <tbody>
                                <template>
                                    <tr v-bind:class="{'active':selected.hasOwnProperty(d.program_id)}" v-on:click="select(d.program_id,d.program_name)" v-for="(d,index) in list" class="list-item" v-bind:data-id="d.program_id">
                                        <td>{{index+1}}、《<span class="title">{{d.program_name}}</span>》</td>
                                        <td>{{d.platform_name}}</td>
                                        <td>总评得分：{{d.program_score}}</td>
                                        <td>{{d.time}}</td>
                                        <td><a v-on:click="intro(d.program_id)" href="javascript:;" class="item-more" v-bind:data-id="d.program_id">内容简介</a></td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center">
                        <br>
                        <br>
                        <button type="button" class="pure-btn btn-large btn-submit" id="btn_weights"> 确 定</button>
                        <br>
                    </div>
                </div>
            </div>
            <div id="no-result" class="result-list tip-nothing" style="display:none">
                <p class="tip-ico-mark">根据您所选的限制条件，系统内暂时没有相对应的内容，请调整您的限制条件</p>
            </div>
        </div>
        <div class="content">
            <div class="control-box" id="controlBox" style="display:none;">
                <h3 class="title">权重配比</h3>
                <div class="control-num">当前权重：<span class="NUM">100%</span></div>
                <br><br>
                <div class="pure-g-c" id="weights"></div>
                <div class="clear"></div>
                <div class="control-btns">
                    <br>
                    <button type="button" class="pure-btn btn-large btn-submit" id="confirm">确认提交</button>
                </div>
            </div>
        </div>
        <?= $this->render('../module/footer') ?>
