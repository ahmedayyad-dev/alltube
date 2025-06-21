{extends file='page.tpl'}
{block name='main'}
    <div>
        {html_image file='img/logo.png' path_prefix={base_url}|cat:'/' alt=$config->appName class="logo"}
    </div>
    <form action="{path_for name="info"}">
        <label class="labelurl" for="url">
            {t}Copy here the URL of your video (YouTube, Dailymotion, etc.){t}
        </label>
        <div class="champs">
            <span class="URLinput_wrapper">
                <!-- We used to have an autofocus attribute on this field but it triggerd a very specific CSS bug: https://github.com/Rudloff/alltube/issues/117 -->
                <input class="URLinput large-font" type="url" name="url" id="url"
                       required placeholder="https://example.com/video"/>
            </span>
            {if $config->uglyUrls}
                <input type="hidden" name="page" value="info"/>
            {/if}
            <input class="downloadBtn large-font" type="submit" value="{t}Download{/t}"/><br/>
            {if $config->convert}
                <div class="mp3 small-font">
                    <div class="mp3-inner">
                        <input type="checkbox" id="audio" class="audio"
                               name="audio" {($config->defaultAudio) ? 'checked' : ''}>
                        <label for="audio"><span class="ui"></span>
                            {t}Audio only (MP3){/t}
                        </label>
                        {if $config->convertSeek}
                            <div class="seekOptions">
                                <label for="from">{t}From{/t}</label>
                                <input type="text" pattern="(\\d+:)?(\\d+:)?\\d+(\\.\\d+)?"
                                       placeholder="HH:MM:SS" name="from" id="from"/>
                                <label for="to">{t}to{/t}</label>
                                <input type="text" pattern="(\\d+:)?(\\d+:)?\\d+(\\.\\d+)?"
                                       placeholder="HH:MM:SS" name="to" id="to"/>
                            </div>
                        {/if}
                    </div>
                </div>
            {/if}
        </div>
    </form>

    <a class="combatiblelink small-font" href="{path_for name="extractors"}">{t}See all supported websites{/t}</a>

    <div id="bookmarklet" class="bookmarklet_wrapper">
        <p>{t}Drag this to your bookmarks bar:{/t}</p>
        <a class="bookmarklet large-font"
           href="javascript:window.location='{$domain}{path_for name='info' queryParams=['url' => '%url%']}'.replace('%url%', encodeURIComponent(location.href));">
            {t}Bookmarklet{/t}
        </a>
    </div>

    <div id="api_link" class="api_wrapper" style="margin-top:1rem; text-align:center;">
        <a href="https://rapidapi.com/ahmedyad200/api/youtube-to-telegram-uploader-api" target="_blank" class="api_button">
            <!-- Option 1: Use RapidAPI default logo -->
            <img src="https://rapidapi.com/static-assets/default/logo-blue.svg"
                 alt="RapidAPI - YouTube to Telegram Uploader API" class="apiLogo" style="max-width:150px; height:auto; cursor:pointer; transition: transform 0.2s ease;"/>

            <!-- Option 2: Text-based button (comment out the img above and uncomment below) -->
            <!-- <div class="api_text_button" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; border-radius: 8px; font-weight: bold; display: inline-block; text-decoration: none; transition: transform 0.2s ease;">
                📡 Access Our API
            </div> -->
        </a>
        <p class="small-font" style="margin-top: 0.5rem; color: #666;">
            {t}Access our API for developers{/t}
        </p>
    </div>

    <style>
        .api_button img:hover {
            transform: scale(1.05);
            opacity: 0.8;
        }

        .api_text_button:hover {
            transform: scale(1.05);
        }

        .api_wrapper {
            border-top: 1px solid #eee;
            padding-top: 1rem;
        }
    </style>
{/block}