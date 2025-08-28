#!/usr/bin/env python3
"""
Conversor Excel para CSV otimizado para Laravel
===============================================

Este script é otimizado para ser chamado pelo Laravel via exec()
"""

import pandas as pd
import sys
import os
import json
from datetime import datetime

class ConversorLaravel:
    """Conversor otimizado para Laravel"""
    
    def __init__(self):
        self.resultado = {
            'sucesso': False,
            'mensagem': '',
            'arquivo_saida': '',
            'tipos_detectados': {},
            'resumo': {}
        }
    
    def converter(self, arquivo_entrada, arquivo_saida, delimitador=','):
        """
        Converte Excel para CSV e retorna resultado em JSON
        """
        try:
            # Verificar arquivo de entrada
            if not os.path.exists(arquivo_entrada):
                self.resultado['mensagem'] = f"Arquivo não encontrado: {arquivo_entrada}"
                return self._retornar_json()
            
            # Determinar engine baseado na extensão
            extensao = os.path.splitext(arquivo_entrada)[1].lower()
            
            if extensao == '.xls':
                try:
                    df = pd.read_excel(arquivo_entrada, engine='xlrd')
                except:
                    df = pd.read_excel(arquivo_entrada, engine='openpyxl')
            else:
                try:
                    df = pd.read_excel(arquivo_entrada, engine='openpyxl')
                except:
                    df = pd.read_excel(arquivo_entrada, engine='xlrd')
            
            # Detectar tipos
            tipos = self._detectar_tipos(df)
            
            # Converter datas
            df = self._converter_datas(df, tipos)
            
            # Salvar CSV
            df.to_csv(arquivo_saida, index=False, sep=delimitador, encoding='utf-8')
            
            # Preparar resultado
            self.resultado['sucesso'] = True
            self.resultado['mensagem'] = 'Conversão realizada com sucesso'
            self.resultado['arquivo_saida'] = arquivo_saida
            self.resultado['tipos_detectados'] = tipos
            self.resultado['resumo'] = {
                'linhas': len(df),
                'colunas': len(df.columns),
                'colunas_data': len([t for t in tipos.values() if 'data' in str(t)]),
                'colunas_numero': len([t for t in tipos.values() if 'numero' in str(t)]),
                'colunas_texto': len([t for t in tipos.values() if 'texto' in str(t)])
            }
            
        except Exception as e:
            self.resultado['mensagem'] = f"Erro: {str(e)}"
        
        return self._retornar_json()
    
    def _detectar_tipos(self, df):
        """Detecta tipos de dados das colunas"""
        tipos = {}
        
        for coluna in df.columns:
            if pd.api.types.is_datetime64_any_dtype(df[coluna]):
                tipos[coluna] = 'data'
            elif pd.api.types.is_numeric_dtype(df[coluna]):
                tipos[coluna] = 'numero'
            elif pd.api.types.is_bool_dtype(df[coluna]):
                tipos[coluna] = 'booleano'
            elif pd.api.types.is_categorical_dtype(df[coluna]):
                tipos[coluna] = 'categoria'
            else:
                # Verificar se parece ser data
                if self._parece_ser_data(df[coluna]):
                    tipos[coluna] = 'data_texto'
                else:
                    tipos[coluna] = 'texto'
        
        return tipos
    
    def _parece_ser_data(self, serie):
        """Verifica se parece ser data"""
        if serie.empty:
            return False
        
        amostra = serie.dropna().head(5)
        if amostra.empty:
            return False
        
        # Padrões de data comuns
        import re
        padroes = [
            r'\d{1,2}/\d{1,2}/\d{2,4}',
            r'\d{1,2}-\d{1,2}-\d{2,4}',
            r'\d{4}-\d{1,2}-\d{1,2}'
        ]
        
        for padrao in padroes:
            if amostra.astype(str).str.match(padrao).any():
                return True
        
        return False
    
    def _converter_datas(self, df, tipos):
        """Converte colunas de data"""
        for coluna, tipo in tipos.items():
            if 'data' in str(tipo):
                try:
                    if tipo == 'data_texto':
                        df[coluna] = pd.to_datetime(df[coluna], errors='coerce')
                    
                    # Formatar como dd/mm/yyyy
                    df[coluna] = df[coluna].dt.strftime('%d/%m/%Y')
                except:
                    pass  # Manter original se der erro
        
        return df
    
    def _retornar_json(self):
        """Retorna resultado em JSON"""
        return json.dumps(self.resultado, ensure_ascii=False)

def main():
    """Função principal para uso via linha de comando"""
    if len(sys.argv) < 3:
        print("Uso: python conversor_laravel.py entrada.xlsx saida.csv [delimitador]")
        sys.exit(1)
    
    entrada = sys.argv[1]
    saida = sys.argv[2]
    delimitador = sys.argv[3] if len(sys.argv) > 3 else ','
    
    conversor = ConversorLaravel()
    resultado = conversor.converter(entrada, saida, delimitador)
    
    print(resultado)

if __name__ == "__main__":
    main()


