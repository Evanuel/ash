#!/usr/bin/env python3
"""
ASH PROJECT SCANNER
Script completo para levantamento de arquivos do projeto Laravel
"""

import os
import json
import sys
from datetime import datetime
from pathlib import Path
import hashlib
from collections import defaultdict, Counter
import re
from typing import Dict, List, Tuple, Any, Optional

class ProjectScanner:
    """Scanner completo para projetos Laravel"""
    
    def __init__(self, project_path: str = "."):
        self.project_root = Path(project_path).resolve()
        self.output_dir = self.project_root / "project_analysis"
        
        # Diretórios a ignorar
        self.exclude_dirs = {
            'vendor', 'node_modules', '.git', '.idea', '.vscode',
            'bootstrap/cache', 'storage/framework/cache',
            'storage/logs', '__pycache__', 'tmp', 'temp',
            'public/storage', 'public/hot'
        }
        
        # Arquivos a ignorar
        self.exclude_files = {
            '.env', '.env.example', '.gitignore', '.gitattributes',
            '.DS_Store', 'Thumbs.db', 'composer.lock', 'package-lock.json',
            'yarn.lock', '*.log', '*.tmp', '*.swp', '*.swo'
        }
        
        # Resultados da análise
        self.results = {
            'metadata': {
                'scan_date': datetime.now().isoformat(),
                'project_name': self.project_root.name,
                'project_path': str(self.project_root),
                'scanner_version': '2.0.0'
            },
            'statistics': {
                'total_files': 0,
                'total_size_bytes': 0,
                'total_size_human': '',
                'file_types': {},
                'directory_count': 0,
                'php_files': 0,
                'blade_files': 0,
                'js_files': 0,
                'css_files': 0,
                'config_files': 0
            },
            'structure': {
                'models': [],
                'controllers': [],
                'middlewares': [],
                'requests': [],
                'services': [],
                'providers': [],
                'jobs': [],
                'migrations': [],
                'seeders': [],
                'factories': [],
                'views': [],
                'configs': [],
                'routes': []
            },
            'composer': {
                'name': '',
                'description': '',
                'dependencies': {},
                'dev_dependencies': {}
            },
            'routes_analysis': {
                'web_routes': [],
                'api_routes': [],
                'custom_routes': []
            },
            'recent_files': []
        }
    
    def should_exclude(self, path: Path) -> bool:
        """Verifica se o caminho deve ser excluído da análise"""
        try:
            rel_path = str(path.relative_to(self.project_root))
            
            # Verifica diretórios excluídos
            for exclude in self.exclude_dirs:
                if exclude in rel_path:
                    return True
            
            # Verifica padrões de arquivos
            filename = path.name.lower()
            for pattern in self.exclude_files:
                if pattern.startswith('*'):
                    if filename.endswith(pattern[1:]):
                        return True
                elif filename == pattern:
                    return True
            
            return False
        except:
            return True
    
    def format_size(self, bytes_size: int) -> str:
        """Formata bytes para formato legível"""
        for unit in ['B', 'KB', 'MB', 'GB']:
            if bytes_size < 1024.0:
                return f"{bytes_size:.2f} {unit}"
            bytes_size /= 1024.0
        return f"{bytes_size:.2f} TB"
    
    def detect_file_type(self, filepath: Path) -> Tuple[str, str]:
        """Detecta o tipo de arquivo e sua categoria"""
        name = filepath.name.lower()
        rel_path = str(filepath.relative_to(self.project_root))
        
        # Arquivos PHP específicos
        if filepath.suffix.lower() == '.php':
            # Models
            if 'models' in rel_path.lower():
                return 'php-model', 'Model'
            # Controllers
            elif 'controller' in name:
                return 'php-controller', 'Controller'
            # Middlewares
            elif 'middleware' in name:
                return 'php-middleware', 'Middleware'
            # Requests
            elif 'request' in name:
                return 'php-request', 'Request'
            # Services
            elif 'service' in name:
                return 'php-service', 'Service'
            # Providers
            elif 'provider' in name:
                return 'php-provider', 'Provider'
            # Jobs
            elif 'job' in name:
                return 'php-job', 'Job'
            # Migrations
            elif 'migration' in name or '/migrations/' in rel_path:
                return 'php-migration', 'Migration'
            # Seeders
            elif 'seeder' in name:
                return 'php-seeder', 'Seeder'
            # Factories
            elif 'factory' in name:
                return 'php-factory', 'Factory'
            else:
                return 'php', 'PHP'
        
        # Blade templates
        elif filepath.suffix.lower() == '.blade.php':
            return 'blade', 'Blade View'
        
        # JavaScript
        elif filepath.suffix.lower() in ['.js', '.jsx', '.ts', '.tsx', '.vue']:
            return 'javascript', 'JavaScript'
        
        # CSS/SASS
        elif filepath.suffix.lower() in ['.css', '.scss', '.sass', '.less']:
            return 'css', 'CSS'
        
        # Configurações
        elif 'config' in rel_path.lower() or name.endswith('.env'):
            return 'config', 'Configuration'
        
        # JSON
        elif filepath.suffix.lower() == '.json':
            return 'json', 'JSON'
        
        # Rotas
        elif 'routes' in rel_path.lower():
            return 'route', 'Route'
        
        # Default
        return filepath.suffix.lower()[1:] if filepath.suffix else 'unknown', 'Other'
    
    def analyze_php_file(self, filepath: Path) -> Dict[str, Any]:
        """Analisa arquivo PHP para extrair informações"""
        info = {
            'file': str(filepath.relative_to(self.project_root)),
            'classes': [],
            'namespace': '',
            'extends': '',
            'implements': [],
            'traits': [],
            'methods': [],
            'properties': []
        }
        
        try:
            content = filepath.read_text(encoding='utf-8', errors='ignore')
            
            # Busca namespace
            namespace_match = re.search(r'namespace\s+([\w\\]+);', content)
            if namespace_match:
                info['namespace'] = namespace_match.group(1)
            
            # Busca classes
            class_match = re.search(r'class\s+(\w+)', content)
            if class_match:
                info['classes'].append(class_match.group(1))
            
            # Busca extends
            extends_match = re.search(r'extends\s+([\w\\]+)', content)
            if extends_match:
                info['extends'] = extends_match.group(1)
            
            # Busca implements
            implements_match = re.findall(r'implements\s+([\w\\\s,]+)', content)
            if implements_match:
                for impl in implements_match:
                    info['implements'].extend([i.strip() for i in impl.split(',')])
            
            # Busca traits
            traits_match = re.findall(r'use\s+([\w\\]+);', content)
            info['traits'] = traits_match
            
            # Busca métodos públicos
            methods_match = re.findall(r'(public|protected|private)\s+function\s+(\w+)', content)
            info['methods'] = [f"{access} {name}" for access, name in methods_match[:10]]  # Limita a 10
            
        except Exception as e:
            info['error'] = str(e)
        
        return info
    
    def analyze_composer(self) -> None:
        """Analisa composer.json se existir"""
        composer_path = self.project_root / 'composer.json'
        if composer_path.exists():
            try:
                with open(composer_path, 'r', encoding='utf-8') as f:
                    composer_data = json.load(f)
                
                self.results['composer']['name'] = composer_data.get('name', '')
                self.results['composer']['description'] = composer_data.get('description', '')
                self.results['composer']['dependencies'] = composer_data.get('require', {})
                self.results['composer']['dev_dependencies'] = composer_data.get('require-dev', {})
            except:
                pass
    
    def analyze_routes(self) -> None:
        """Analisa arquivos de rota"""
        routes_path = self.project_root / 'routes'
        if routes_path.exists():
            for route_file in routes_path.glob('*.php'):
                try:
                    content = route_file.read_text(encoding='utf-8', errors='ignore')
                    
                    # Encontra definições de rotas
                    route_patterns = [
                        (r'Route::(\w+)\([^)]*["\']([^"\']+)["\']', 'web'),
                        (r'->name\(["\']([^"\']+)["\']\)', 'named'),
                        (r'prefix\(["\']([^"\']+)["\']\)', 'prefix')
                    ]
                    
                    for pattern, route_type in route_patterns:
                        matches = re.findall(pattern, content)
                        for match in matches:
                            if route_type == 'web':
                                method, path = match
                                self.results['routes_analysis']['web_routes'].append({
                                    'method': method.upper(),
                                    'path': path,
                                    'file': route_file.name
                                })
                            elif route_type == 'named':
                                self.results['routes_analysis']['named_routes'].append(match[0])
                            elif route_type == 'prefix':
                                self.results['routes_analysis']['prefixes'].append(match[0])
                
                except:
                    continue
    
    def scan_directory(self, directory: Path = None) -> None:
        """Varre recursivamente o diretório"""
        if directory is None:
            directory = self.project_root
        
        for item in directory.iterdir():
            if self.should_exclude(item):
                continue
            
            if item.is_dir():
                self.results['statistics']['directory_count'] += 1
                self.scan_directory(item)
            else:
                self.process_file(item)
    
    def process_file(self, filepath: Path) -> None:
        """Processa um arquivo individual"""
        try:
            # Estatísticas básicas
            stat = filepath.stat()
            file_size = stat.st_size
            
            self.results['statistics']['total_files'] += 1
            self.results['statistics']['total_size_bytes'] += file_size
            
            # Tipo de arquivo
            file_type, category = self.detect_file_type(filepath)
            
            # Atualiza contadores de tipo
            file_ext = filepath.suffix.lower()
            if file_ext:
                self.results['statistics']['file_types'][file_ext] = \
                    self.results['statistics']['file_types'].get(file_ext, 0) + 1
            
            # Contadores específicos
            if file_type.startswith('php-'):
                self.results['statistics']['php_files'] += 1
            elif file_type == 'blade':
                self.results['statistics']['blade_files'] += 1
            elif file_type == 'javascript':
                self.results['statistics']['js_files'] += 1
            elif file_type == 'css':
                self.results['statistics']['css_files'] += 1
            elif file_type == 'config':
                self.results['statistics']['config_files'] += 1
            
            # Informações específicas por tipo
            file_info = {
                'path': str(filepath.relative_to(self.project_root)),
                'size': file_size,
                'size_human': self.format_size(file_size),
                'modified': datetime.fromtimestamp(stat.st_mtime).isoformat(),
                'type': file_type,
                'category': category
            }
            
            # Adiciona à estrutura apropriada
            if file_type == 'php-model':
                php_info = self.analyze_php_file(filepath)
                file_info.update(php_info)
                self.results['structure']['models'].append(file_info)
            
            elif file_type == 'php-controller':
                php_info = self.analyze_php_file(filepath)
                file_info.update(php_info)
                self.results['structure']['controllers'].append(file_info)
            
            elif file_type == 'php-middleware':
                php_info = self.analyze_php_file(filepath)
                file_info.update(php_info)
                self.results['structure']['middlewares'].append(file_info)
            
            elif file_type == 'php-request':
                php_info = self.analyze_php_file(filepath)
                file_info.update(php_info)
                self.results['structure']['requests'].append(file_info)
            
            elif file_type == 'php-service':
                php_info = self.analyze_php_file(filepath)
                file_info.update(php_info)
                self.results['structure']['services'].append(file_info)
            
            elif file_type == 'php-provider':
                php_info = self.analyze_php_file(filepath)
                file_info.update(php_info)
                self.results['structure']['providers'].append(file_info)
            
            elif file_type == 'php-job':
                php_info = self.analyze_php_file(filepath)
                file_info.update(php_info)
                self.results['structure']['jobs'].append(file_info)
            
            elif file_type == 'php-migration':
                php_info = self.analyze_php_file(filepath)
                file_info.update(php_info)
                self.results['structure']['migrations'].append(file_info)
            
            elif file_type == 'php-seeder':
                php_info = self.analyze_php_file(filepath)
                file_info.update(php_info)
                self.results['structure']['seeders'].append(file_info)
            
            elif file_type == 'php-factory':
                php_info = self.analyze_php_file(filepath)
                file_info.update(php_info)
                self.results['structure']['factories'].append(file_info)
            
            elif file_type == 'blade':
                self.results['structure']['views'].append(file_info)
            
            elif file_type == 'config':
                self.results['structure']['configs'].append(file_info)
            
            elif file_type == 'route':
                self.results['structure']['routes'].append(file_info)
            
            # Arquivos recentes (últimos 7 dias)
            modified_days = (datetime.now() - datetime.fromtimestamp(stat.st_mtime)).days
            if modified_days <= 7:
                self.results['recent_files'].append(file_info)
        
        except Exception as e:
            print(f"⚠️ Erro ao processar {filepath}: {e}")
    
    def generate_text_report(self) -> str:
        """Gera relatório em formato texto"""
        report = []
        report.append("=" * 80)
        report.append("📊 PROJETO ASH - RELATÓRIO COMPLETO DE ANÁLISE")
        report.append("=" * 80)
        
        # Metadados
        metadata = self.results['metadata']
        report.append(f"📅 Data da análise: {metadata['scan_date']}")
        report.append(f"📁 Projeto: {metadata['project_name']}")
        report.append(f"📂 Diretório: {metadata['project_path']}")
        report.append("")
        
        # Estatísticas
        stats = self.results['statistics']
        report.append("📈 ESTATÍSTICAS GERAIS")
        report.append("-" * 40)
        report.append(f"• Total de arquivos: {stats['total_files']:,}")
        report.append(f"• Total de diretórios: {stats['directory_count']:,}")
        report.append(f"• Tamanho total: {self.format_size(stats['total_size_bytes'])}")
        report.append("")
        
        # Tipos de arquivo
        report.append("📁 DISTRIBUIÇÃO POR TIPO DE ARQUIVO")
        report.append("-" * 40)
        for ext, count in sorted(stats['file_types'].items(), key=lambda x: x[1], reverse=True):
            percentage = (count / stats['total_files']) * 100 if stats['total_files'] > 0 else 0
            report.append(f"  {ext:10} → {count:6,} arquivos ({percentage:5.1f}%)")
        
        report.append("")
        
        # Arquivos PHP específicos
        report.append("🐘 ARQUIVOS PHP (DETALHADO)")
        report.append("-" * 40)
        report.append(f"• PHP Files total: {stats['php_files']:,}")
        report.append(f"• Blade Views: {stats['blade_files']:,}")
        report.append(f"• JavaScript Files: {stats['js_files']:,}")
        report.append(f"• CSS Files: {stats['css_files']:,}")
        report.append(f"• Config Files: {stats['config_files']:,}")
        report.append("")
        
        # Models
        models = self.results['structure']['models']
        if models:
            report.append("🗃️  MODELS ENCONTRADOS")
            report.append("-" * 40)
            for model in sorted(models, key=lambda x: x['path']):
                class_name = model['classes'][0] if model['classes'] else 'Unknown'
                report.append(f"  • {class_name:30} → {model['path']}")
            report.append("")
        
        # Controllers
        controllers = self.results['structure']['controllers']
        if controllers:
            report.append("🎮  CONTROLLERS ENCONTRADOS")
            report.append("-" * 40)
            for controller in sorted(controllers, key=lambda x: x['path']):
                class_name = controller['classes'][0] if controller['classes'] else 'Unknown'
                report.append(f"  • {class_name:30} → {controller['path']}")
            report.append("")
        
        # Services
        services = self.results['structure']['services']
        if services:
            report.append("⚙️  SERVICES ENCONTRADOS")
            report.append("-" * 40)
            for service in sorted(services, key=lambda x: x['path']):
                class_name = service['classes'][0] if service['classes'] else 'Unknown'
                report.append(f"  • {class_name:30} → {service['path']}")
            report.append("")
        
        # Migrations
        migrations = self.results['structure']['migrations']
        if migrations:
            report.append("🔄  MIGRAÇÕES (últimas 10)")
            report.append("-" * 40)
            for migration in sorted(migrations, key=lambda x: x['path'], reverse=True)[:10]:
                filename = Path(migration['path']).name
                report.append(f"  • {filename}")
            report.append("")
        
        # Dependências do Composer
        if self.results['composer']['dependencies']:
            report.append("📦 DEPENDÊNCIAS DO COMPOSER (principais)")
            report.append("-" * 40)
            deps = self.results['composer']['dependencies']
            for dep, version in list(deps.items())[:15]:
                report.append(f"  • {dep:40} → {version}")
            report.append("")
        
        # Arquivos recentes
        recent = self.results['recent_files']
        if recent:
            report.append("🕐 ARQUIVOS MODIFICADOS RECENTEMENTE (últimos 7 dias)")
            report.append("-" * 40)
            for file_info in sorted(recent, key=lambda x: x['modified'], reverse=True)[:15]:
                days_ago = (datetime.now() - datetime.fromisoformat(file_info['modified'])).days
                report.append(f"  • {file_info['path']:50} → {days_ago} dias atrás")
        
        report.append("")
        report.append("=" * 80)
        report.append("✅ ANÁLISE COMPLETADA")
        report.append("=" * 80)
        
        return "\n".join(report)
    
    def generate_markdown_context(self) -> str:
        """Gera arquivo de contexto para DeepSeek em Markdown"""
        md = []
        md.append(f"""# 🚀 PROJETO ASH - CONTEXTO COMPLETO

## 📋 METADADOS DO PROJETO
- **Nome do Projeto**: {self.results['metadata']['project_name']}
- **Data da Análise**: {self.results['metadata']['scan_date']}
- **Localização**: `{self.results['metadata']['project_path']}`
- **Scanner**: v{self.results['metadata']['scanner_version']}

## 📊 ESTATÍSTICAS
- **Total de Arquivos**: {self.results['statistics']['total_files']:,}
- **Total de Diretórios**: {self.results['statistics']['directory_count']:,}
- **Tamanho Total**: {self.format_size(self.results['statistics']['total_size_bytes'])}
- **Arquivos PHP**: {self.results['statistics']['php_files']:,}
- **Views Blade**: {self.results['statistics']['blade_files']:,}
- **Arquivos JavaScript**: {self.results['statistics']['js_files']:,}

## 🗃️ ESTRUTURA DO PROJETO
""")
        
        # Models
        if self.results['structure']['models']:
            md.append("### 📦 Models")
            for model in self.results['structure']['models']:
                class_name = model['classes'][0] if model['classes'] else 'Unknown'
                md.append(f"- **{class_name}** → `{model['path']}`")
                if model.get('namespace'):
                    md.append(f"  - *Namespace*: `{model['namespace']}`")
                if model.get('extends'):
                    md.append(f"  - *Extends*: `{model['extends']}`")
            md.append("")
        
        # Controllers
        if self.results['structure']['controllers']:
            md.append("### 🎮 Controllers")
            for controller in self.results['structure']['controllers']:
                class_name = controller['classes'][0] if controller['classes'] else 'Unknown'
                md.append(f"- **{class_name}** → `{controller['path']}`")
            md.append("")
        
        # Services
        if self.results['structure']['services']:
            md.append("### ⚙️ Services")
            for service in self.results['structure']['services']:
                class_name = service['classes'][0] if service['classes'] else 'Unknown'
                md.append(f"- **{class_name}** → `{service['path']}`")
            md.append("")
        
        # Outras estruturas
        structures = [
            ('🔄 Migrations', 'migrations'),
            ('🔐 Middlewares', 'middlewares'),
            ('📝 Requests', 'requests'),
            ('🚀 Jobs', 'jobs'),
            ('🏗️ Providers', 'providers'),
            ('🌱 Seeders', 'seeders'),
            ('🏭 Factories', 'factories')
        ]
        
        for title, key in structures:
            items = self.results['structure'][key]
            if items:
                md.append(f"### {title}")
                for item in items[:10]:  # Limita a 10 itens
                    class_name = item['classes'][0] if item.get('classes') else Path(item['path']).name
                    md.append(f"- `{item['path']}`")
                if len(items) > 10:
                    md.append(f"  *... e mais {len(items) - 10} itens*")
                md.append("")
        
        # Dependências
        if self.results['composer']['dependencies']:
            md.append("## 📦 DEPENDÊNCIAS")
            deps = self.results['composer']['dependencies']
            laravel_deps = {k: v for k, v in deps.items() if 'laravel' in k.lower()}
            other_deps = {k: v for k, v in deps.items() if 'laravel' not in k.lower()}
            
            if laravel_deps:
                md.append("### Laravel & Framework")
                for dep, version in laravel_deps.items():
                    md.append(f"- `{dep}`: {version}")
                md.append("")
            
            if other_deps:
                md.append("### Outras Dependências")
                for dep, version in list(other_deps.items())[:15]:
                    md.append(f"- `{dep}`: {version}")
                if len(other_deps) > 15:
                    md.append(f"  *... e mais {len(other_deps) - 15} dependências*")
                md.append("")
        
        # Arquivos recentes
        if self.results['recent_files']:
            md.append("## 🕐 TRABALHO RECENTE")
            md.append("Arquivos modificados nos últimos 7 dias:")
            for file_info in self.results['recent_files'][:20]:
                days_ago = (datetime.now() - datetime.fromisoformat(file_info['modified'])).days
                md.append(f"- `{file_info['path']}` ({days_ago} dias atrás)")
            md.append("")
        
        md.append(f"""## 🎯 USO COM ASSISTENTES DE IA

            Quando solicitar ajuda sobre este projeto, inclua:
            CONTEXTO DO PROJETO ASH:

            Projeto: {self.results['metadata']['project_name']}

            Total de arquivos: {self.results['statistics']['total_files']:,}

            Principais modelos: {', '.join([m['classes'][0] for m in self.results['structure']['models'][:3] if m['classes']])}

            Controllers: {len(self.results['structure']['controllers'])}

            Framework: Laravel

            OBJETIVO ATUAL: [Descreva o que está tentando fazer]
            ARQUIVOS ENVOLVIDOS: [Mencione arquivos específicos se aplicável]
            ---

            *Documento gerado automaticamente em {datetime.now().isoformat()}*
            """)
       
        return "\n".join(md)
    
    def save_results(self) -> Dict[str, str]:
        """Salva todos os resultados em arquivos"""
        # Cria diretório de saída
        self.output_dir.mkdir(exist_ok=True)
        
        output_files = {}
        
        # 1. JSON completo
        json_path = self.output_dir / 'ash_analysis_full.json'
        with open(json_path, 'w', encoding='utf-8') as f:
            json.dump(self.results, f, indent=2, ensure_ascii=False)
        output_files['json_full'] = str(json_path)
        
        # 2. JSON simplificado (apenas estrutura)
        simple_data = {
            'metadata': self.results['metadata'],
            'statistics': self.results['statistics'],
            'models': [{'class': m['classes'][0] if m['classes'] else 'Unknown', 
                       'path': m['path']} 
                      for m in self.results['structure']['models']],
            'controllers': [{'class': c['classes'][0] if c['classes'] else 'Unknown', 
                            'path': c['path']} 
                           for c in self.results['structure']['controllers']]
        }
        
        simple_path = self.output_dir / 'ash_analysis_simple.json'
        with open(simple_path, 'w', encoding='utf-8') as f:
            json.dump(simple_data, f, indent=2, ensure_ascii=False)
        output_files['json_simple'] = str(simple_path)
        
        # 3. Relatório em texto
        text_report = self.generate_text_report()
        text_path = self.output_dir / 'ash_analysis_report.txt'
        text_path.write_text(text_report, encoding='utf-8')
        output_files['text_report'] = str(text_path)
        
        # 4. Contexto para DeepSeek (Markdown)
        md_context = self.generate_markdown_context()
        md_path = self.output_dir / 'ASH_PROJECT_CONTEXT.md'
        md_path.write_text(md_context, encoding='utf-8')
        output_files['md_context'] = str(md_path)
        
        # 5. Lista de arquivos
        files_list = []
        for category, items in self.results['structure'].items():
            if items:
                files_list.append(f"\n{category.upper()}:")
                for item in items[:20]:  # Limita a 20 por categoria
                    files_list.append(f"  {item['path']}")
        
        list_path = self.output_dir / 'ash_files_list.txt'
        list_path.write_text("\n".join(files_list), encoding='utf-8')
        output_files['files_list'] = str(list_path)
        
        return output_files
    
    def run(self) -> Dict[str, Any]:
        """Executa a análise completa"""
        print("🔍 Iniciando análise do projeto ASH...")
        print(f"📁 Diretório: {self.project_root}")
        print("⏳ Analisando estrutura do projeto...")
        
        # Análises
        self.analyze_composer()
        self.analyze_routes()
        self.scan_directory()
        
        # Atualiza tamanho total formatado
        self.results['statistics']['total_size_human'] = \
            self.format_size(self.results['statistics']['total_size_bytes'])
        
        # Salva resultados
        output_files = self.save_results()
        
        # Gera relatório final
        report = self.generate_text_report()
        print("\n" + report)
        
        print("\n" + "=" * 80)
        print("✅ ANÁLISE COMPLETADA COM SUCESSO!")
        print("=" * 80)
        print("\n📁 ARQUIVOS GERADOS:")
        for name, path in output_files.items():
            print(f"  • {name}: {path}")
        
        print(f"\n🎯 Use o arquivo '{output_files['md_context']}' como contexto para o DeepSeek")
        
        return self.results


def main():
    """Função principal"""
    # Verifica argumentos
    if len(sys.argv) > 1:
        project_path = sys.argv[1]
    else:
        project_path = input("Digite o caminho do projeto (ou pressione Enter para atual): ").strip()
        if not project_path:
            project_path = "."
    
    # Verifica se o diretório existe
    if not Path(project_path).exists():
        print(f"❌ Diretório não encontrado: {project_path}")
        sys.exit(1)
    
    # Executa scanner
    try:
        scanner = ProjectScanner(project_path)
        results = scanner.run()
        
        # Sugestão de uso
        print("\n💡 DICA: Copie o conteúdo de 'ASH_PROJECT_CONTEXT.md'")
        print("       e use no início das conversas com o DeepSeek!")
        
    except KeyboardInterrupt:
        print("\n\n⚠️ Análise interrompida pelo usuário")
        sys.exit(0)
    except Exception as e:
        print(f"\n❌ Erro durante a análise: {e}")
        sys.exit(1)


if __name__ == "__main__":
    main()