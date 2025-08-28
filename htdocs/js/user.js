/**
 * ���[�U�T�C�g�S�̂ɓK�p�����JS�t�@�C���B
 */

function body_onLoadGlobal() {

    // "pc-style" �Ƃ��������������Ă���v�f�����ׂĎ擾�B
    var list = document.querySelectorAll("[pc-style],[pc_style]");

    // ������Ă����A"pc-style" �� "style" �ɏ悹�ւ���B
    for(var i = 0 ; i < list.length ; i++) {
        var node = list[i];
        var style1 = node.getAttribute("style");
        var style2 = node.getAttribute("pc-style");
        var style3 = node.getAttribute("pc_style");
        node.setAttribute("style", (style1 ? style1 : "") + "; " + (style2 ? style2 : "") + "; " + (style3 ? style3 : ""));
    }

    // body_onLoad �Ƃ����֐�������ꍇ�̓R�[������B
    if(window.body_onLoad)
        body_onLoad();
}

document.addEventListener( "DOMContentLoaded", body_onLoadGlobal, false );
