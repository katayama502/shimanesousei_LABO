# Minna-no-Bukatsu Scaffold

This repository contains the scaffolding files and setup script for the "みんなの部活応援隊" MVP built with Laravel 10, Bootstrap 5, and AdminLTE.

## セットアップ手順

1. PHP 8.2+、Composer、Node.js/npm、MySQL を用意してください。
2. 次のコマンドを実行して本番用の Laravel プロジェクトを生成します。

```bash
bash scripts/setup.sh
```

`setup.sh` では以下を自動実行します。

- Laravel プロジェクト (`bu-ousen`) の作成
- Laravel Breeze (Blade) と AdminLTE のセットアップ
- `scaffold/` 以下に用意したアプリケーションコード・ビュー・テスト・マイグレーションのコピー
- `.env` のテンプレート配置 (`FILESYSTEM_DISK=public` など)
- `php artisan migrate --seed` によるデータベース初期化とダミーデータ投入
- Vite ビルド (`npm run build`)

セットアップ完了後は `bu-ousen` ディレクトリへ移動し、通常の Laravel プロジェクトとして開発・実行できます。

```bash
cd bu-ousen
php artisan serve
```

## テスト

アプリケーションには主要ユースケースをカバーする Feature テストが含まれています。以下のコマンドで実行できます。

```bash
php artisan test
```

## ディレクトリ構成

- `scripts/setup.sh` – プロジェクト生成・初期化スクリプト
- `scaffold/` – コントローラ、モデル、ポリシー、マイグレーション、シーダ、Blade、Feature テストなどアプリ固有のコード

生成済みプロジェクトは `bu-ousen/` 以下に展開されます (スクリプト実行後)。

## 管理者ダミーアカウント

シーディング後、以下のアカウントが作成されます。

- 管理者: `admin@example.com` / `password`
- クラブ担当者: `club1@example.com` (他に `club2@example.com`, `club3@example.com`)
- 企業担当者: `company1@example.com` (他に `company2@example.com`, `company3@example.com`)

いずれもデフォルトパスワードは `password` です。
