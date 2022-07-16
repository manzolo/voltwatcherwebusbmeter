#!/bin/bash
# vim:sw=4:ts=4:et

if /usr/bin/find "/docker-entrypoint.d/" -mindepth 1 -maxdepth 1 -type f -print -quit 2>/dev/null | read v; then
    echo >&1 "$0: /docker-entrypoint.d/ is not empty, will attempt to perform configuration"

    echo >&1 "$0: Looking for shell scripts in /docker-entrypoint.d/"
    find "/docker-entrypoint.d/" -follow -type f -print | sort -V | while read -r f; do
        case "$f" in
        *.sh)
            if ! [ -x "$f" ]; then
                chmod +x $f
            fi

            echo -e >&1 "\n$0: Launching ${f}\n"
            "$f"
            ;;
        *) echo -e >&1 "\n$0: Ignoring ${f}\n" ;;
        esac
    done

    echo >&1 "$0: Configuration complete; ready for start up"
else
    echo >&1 "$0: No files found in /docker-entrypoint.d/, skipping configuration"
fi

exec "$@"
